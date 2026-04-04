<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\TicketComment;
use App\Models\TicketAttachment;
use App\Models\User;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TicketController extends Controller
{
    /**
     * Show user's tickets (user view).
     */
    public function index()
    {
        $tickets = Auth::user()->tickets()->with('comments', 'attachments')->latest()->paginate(10);
        return view('tickets.index', compact('tickets'));
    }

    /**
     * Show all tickets (admin view).
     */
    public function adminIndex(Request $request)
    {
        $this->authorizeAdmin();
        
        $query = Ticket::with('user', 'assignedTo', 'comments');

        if ($request->filled('id')) {
            $query->where('id', $request->input('id'));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->input('priority'));
        }

        if ($request->filled('category')) {
            $query->where('category', $request->input('category'));
        }

        $tickets = $query->latest()->paginate(15)->appends($request->query());
        
        return view('tickets.admin-index', compact('tickets'));
    }

    /**
     * Show create ticket form.
     */
    public function create()
    {
        return view('tickets.create');
    }

    /**
     * Store a new ticket.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'class_department' => 'required|string|max:255',
            'category' => 'required|in:hardware,software,network,other',
            'title' => 'required|string|max:255',
            'description' => 'required|string|min:10',
            'priority' => 'required|in:low,medium,high,urgent',
            'attachments.*' => 'file|max:10240', // 10MB max per file
        ]);

        $ticket = Auth::user()->tickets()->create([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'class_department' => $validated['class_department'],
            'category' => $validated['category'],
            'title' => $validated['title'],
            'description' => $validated['description'],
            'priority' => $validated['priority'],
        ]);

        // Handle file uploads
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('tickets/' . $ticket->id, 'public');
                TicketAttachment::create([
                    'ticket_id' => $ticket->id,
                    'file_path' => $path,
                    'file_name' => $file->getClientOriginalName(),
                    'mime_type' => $file->getMimeType(),
                    'size' => $file->getSize(),
                    'uploaded_by' => Auth::id(),
                ]);
            }
        }

        return redirect()->route('tickets.show', $ticket)
            ->with('success', 'Biļete veiksmīgi izveidota!');
    }

    /**
     * Show a specific ticket.
     */
    public function show(Ticket $ticket)
    {
        // Check authorization
        if (Auth::user()->id !== $ticket->user_id && !Auth::user()->isAdmin()) {
            abort(403);
        }

        $ticket->load('user', 'comments.user', 'attachments', 'assignedTo');
        $itStaff = Auth::user()->isAdmin()
            ? User::where('role', 'admin')->orderBy('name')->get()
            : collect();

        return view('tickets.show', compact('ticket', 'itStaff'));
    }

    /**
     * Show edit form.
     */
    public function edit(Ticket $ticket)
    {
        // Only user who created it can edit (if not closed)
        if (Auth::user()->id !== $ticket->user_id) {
            abort(403);
        }

        if ($ticket->status === 'closed') {
            abort(403, 'Neiespējams rediģēt noslēgtu biļeti.');
        }

        return view('tickets.edit', compact('ticket'));
    }

    /**
     * Update ticket.
     */
    public function update(Request $request, Ticket $ticket)
    {
        // Check authorization
        if (Auth::user()->id !== $ticket->user_id && !Auth::user()->isAdmin()) {
            abort(403);
        }

        $validated = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'class_department' => 'required|string|max:255',
            'category' => 'required|in:hardware,software,network,other',
            'title' => 'required|string|max:255',
            'description' => 'required|string|min:10',
            'priority' => 'required|in:low,medium,high,urgent',
            'attachments.*' => 'file|max:10240',
        ]);

        $ticket->update($validated);

        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('tickets/' . $ticket->id, 'public');
                TicketAttachment::create([
                    'ticket_id' => $ticket->id,
                    'file_path' => $path,
                    'file_name' => $file->getClientOriginalName(),
                    'mime_type' => $file->getMimeType(),
                    'size' => $file->getSize(),
                    'uploaded_by' => Auth::id(),
                ]);
            }
        }

        return redirect()->route('tickets.show', $ticket)
            ->with('success', 'Biļete veiksmīgi atjaunota!');
    }

    /**
     * Update ticket status (admin only).
     */
    public function updateStatus(Request $request, Ticket $ticket)
    {
        $this->authorizeAdmin();

        $validated = $request->validate([
            'status' => 'required|in:open,in_progress,resolved,closed',
        ]);

        $ticket->update(['status' => $validated['status']]);

        return back()->with('success', 'Statuss atjaunots!');
    }

    /**
     * Assign ticket to IT staff.
     */
    public function assign(Request $request, Ticket $ticket)
    {
        $this->authorizeAdmin();

        $validated = $request->validate([
            'assigned_to' => 'required|exists:users,id',
        ]);

        $ticket->update(['assigned_to' => $validated['assigned_to']]);

        return back()->with('success', 'Biļete piešķirta!');
    }

    /**
     * Download an attachment (owner or admin only).
     */
    public function downloadAttachment(TicketAttachment $attachment)
    {
        $ticket = $attachment->ticket()->with('user')->first();

        if (!Auth::user()->isAdmin() && Auth::id() !== $ticket->user_id) {
            abort(403);
        }

        if (!Storage::disk('public')->exists($attachment->file_path)) {
            abort(404);
        }

        return Storage::disk('public')->download($attachment->file_path, $attachment->file_name);
    }

    /**
     * Delete attachment.
     * Only deletes the attachment record and file, NOT the ticket itself.
     */
    public function destroyAttachment(TicketAttachment $attachment)
    {
        $ticket = $attachment->ticket()->with('user')->first();

        if (!Auth::user()->isAdmin() && Auth::id() !== $ticket->user_id) {
            abort(403);
        }

        // Delete the physical file from storage
        if (Storage::disk('public')->exists($attachment->file_path)) {
            Storage::disk('public')->delete($attachment->file_path);
        }

        // Delete only the attachment record, not the ticket
        $attachment->delete();

        // Return JSON response for AJAX requests
        if (request()->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Pielikums veiksmīgi dzēsts!']);
        }

        return redirect()->route('tickets.edit', $ticket)->with('success', 'Pielikums veiksmīgi dzēsts!');
    }

    /**
     * Delete ticket.
     */
    public function destroy(Ticket $ticket)
    {
        // User can delete own ticket, admin can delete any
        if (Auth::user()->id !== $ticket->user_id && !Auth::user()->isAdmin()) {
            abort(403);
        }

        $ticket->delete();

        return redirect()->route('tickets.index')
            ->with('success', 'Biļete dzēsta!');
    }

    /**
     * Show calendar view (admin only).
     */
    public function calendar()
    {
        $this->authorizeAdmin();

        $tickets = Ticket::all();
        $tz = 'Europe/Riga';
        $calendarTickets = $tickets->map(function ($ticket) use ($tz) {
            return [
                'id' => $ticket->id,
                'priority' => $ticket->priority,
                'created_date_local' => $ticket->created_at->timezone($tz)->toDateString(),
            ];
        });
        
        $stats = [
            'total' => $tickets->count(),
            'urgent' => $tickets->where('priority', 'urgent')->count(),
            'open' => Ticket::where('status', 'open')->orWhere('status', 'in_progress')->count(),
            'closed' => $tickets->where('status', 'closed')->count(),
        ];
        
        return view('tickets.calendar', compact('tickets', 'stats', 'calendarTickets'));
    }

    /**
     * Export calendar view to PDF (admin only).
     */
    public function calendarExport()
    {
        $this->authorizeAdmin();

        if (!class_exists(Pdf::class)) {
            abort(500, 'PDF eksportam nepieciešams barryvdh/laravel-dompdf.');
        }

        $tickets = Ticket::all();
        $tz = 'Europe/Riga';
        $now = Carbon::now($tz);
        $months = [];

        for ($i = 0; $i < 3; $i++) {
            $monthStart = $now->copy()->startOfMonth()->addMonths($i);
            $monthEnd = $monthStart->copy()->endOfMonth();
            $days = [];

            for ($day = 1; $day <= $monthEnd->day; $day++) {
                $date = $monthStart->copy()->day($day);
                $dateStr = $date->toDateString();

                $dayTickets = $tickets->filter(function ($ticket) use ($dateStr, $tz) {
                    return $ticket->created_at->timezone($tz)->toDateString() === $dateStr;
                })->values();

                $days[] = [
                    'date' => $date->copy(),
                    'tickets' => $dayTickets,
                ];
            }

            $months[] = [
                'month' => $monthStart->copy(),
                'days' => $days,
            ];
        }

        $pdf = Pdf::loadView('tickets.calendar-pdf', [
            'months' => $months,
        ]);

        return $pdf->download('tickets-calendar.pdf');
    }

    /**
     * Authorize admin access.
     */
    private function authorizeAdmin()
    {
        if (!Auth::user()->isAdmin()) {
            abort(403, 'Tikai IT personāls var piekļūt šim resursam.');
        }
    }
}
