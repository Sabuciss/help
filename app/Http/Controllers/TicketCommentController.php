<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\TicketComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketCommentController extends Controller
{
    /**
     * Store a comment on a ticket.
     */
    public function store(Request $request, Ticket $ticket)
    {
        // Check authorization
        if (Auth::user()->id !== $ticket->user_id && !Auth::user()->isAdmin()) {
            abort(403);
        }

        $validated = $request->validate([
            'comment' => 'required|string|min:2|max:1000',
        ]);

        TicketComment::create([
            'ticket_id' => $ticket->id,
            'user_id' => Auth::id(),
            'comment' => $validated['comment'],
        ]);

        return back()->with('success', 'Komentārs pievienots!');
    }

    /**
     * Delete a comment.
     */
    public function destroy(TicketComment $comment)
    {
        // Only comment author or admin can delete
        if (Auth::user()->id !== $comment->user_id) {
            abort(403);
        }

        $ticketId = $comment->ticket_id;
        $comment->delete();

        return back()->with('success', 'Komentārs dzēsts!');
    }
}
