@extends('layouts.app')

@section('title', 'Biļešu kalendārs')

@section('content')
<div class="breadcrumb">
    <a href="/">Sākums</a> / <a href="{{ route('tickets.admin-index') }}">Visas biļetes</a> / Kalendārs
</div>

<div class="card">
    <div class="card-header">
        <h2>Biļešu skaits pa dienām</h2>
        <a href="{{ route('tickets.calendar-export') }}" class="btn btn-secondary">Eksportēt PDF</a>
    </div>

    <div id="calendar" style="display: grid; grid-template-columns: 1fr; gap: 2rem; margin: 2rem 0;">
        <!-- Calendar will be generated here -->
    </div>
    <style>
        #calendar {
            display: grid !important;
            grid-template-columns: 1fr !important;
            gap: 2rem !important;
            margin: 2rem 0 !important;
        }
    </style>
</div>

<div class="card">
    <h3>Darbības noslodze</h3>
    <div class="grid">
        <div class="stat-box">
            <div class="stat-box-number">{{ $stats['total'] }}</div>
            <div class="stat-box-label">Kopējās biļetes</div>
        </div>
        <div class="stat-box">
            <div class="stat-box-number">{{ $stats['urgent'] }}</div>
            <div class="stat-box-label">Steidzamas biļetes</div>
        </div>
        <div class="stat-box">
            <div class="stat-box-number">{{ $stats['open'] }}</div>
            <div class="stat-box-label">Neizpildītas biļetes</div>
        </div>
        <div class="stat-box">
            <div class="stat-box-number">{{ $stats['closed'] }}</div>
            <div class="stat-box-label">Noslēgtas biļetes</div>
        </div>
    </div>
</div>

<script>
function renderMonth(year, month, tickets, now) {
    const monthNames = ['Janvāris', 'Februāris', 'Marts', 'Aprīlis', 'Maijs', 'Jūnijs',
                       'Jūlijs', 'Augusts', 'Septembris', 'Oktobris', 'Novembris', 'Decembris'];
    const dayNames = ['Sv', 'P', 'O', 'T', 'C', 'P', 'S'];
    
    const firstDay = new Date(year, month, 1);
    const lastDay = new Date(year, month + 1, 0);
    const daysInMonth = lastDay.getDate();
    const startingDayOfWeek = firstDay.getDay();
    
    let monthHTML = `
        <div style="grid-column: 1 / -1; display: grid; grid-template-columns: repeat(7, 1fr); gap: 1rem; padding: 1rem; background: #fff; border-radius: 5px;">
            <div style="grid-column: 1 / -1; text-align: center; margin-bottom: 1rem; padding: 1rem; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 5px; margin: 0;">
                <h3 style="margin: 0;">${monthNames[month]} ${year}</h3>
            </div>
    `;
    
    // Day headers
    dayNames.forEach(day => {
        monthHTML += `<div style="text-align: center; font-weight: bold; padding: 0.5rem; background: #f0f0f0; border-radius: 3px;">${day}</div>`;
    });
    
    // Empty cells before month starts
    for (let i = 0; i < startingDayOfWeek; i++) {
        monthHTML += `<div></div>`;
    }
    
    // Days of month
    for (let day = 1; day <= daysInMonth; day++) {
        const date = new Date(year, month, day);
        const dateStr = date.toISOString().split('T')[0];
        
        const dayTickets = tickets.filter(t => {
            const ticketDate = new Date(t.created_at).toISOString().split('T')[0];
            return ticketDate === dateStr;
        });
        
        const isToday = date.toDateString() === now.toDateString();
        
        let backgroundColor = '#f9f9f9';
        if (isToday) backgroundColor = '#e3f2fd';
        if (dayTickets.length > 0) backgroundColor = '#fff3cd';
        
        let urgentCount = dayTickets.filter(t => t.priority === 'urgent').length;
        
        monthHTML += `
            <div style="
                background-color: ${backgroundColor};
                border: 1px solid #ddd;
                padding: 0.75rem;
                border-radius: 3px;
                min-height: 80px;
                cursor: pointer;
                transition: background-color 0.3s;
            " onmouseover="this.style.backgroundColor='#ecf0f1'" onmouseout="this.style.backgroundColor='${backgroundColor}'">
                <strong style="display: block; margin-bottom: 0.5rem;">${day}</strong>
                <small style="display: block; color: #7f8c8d;">${dayTickets.length} biļete(s)</small>
                ${urgentCount > 0 ? `<span class="badge badge-urgent" style="font-size: 0.75rem;">${urgentCount} steidzama</span>` : ''}
            </div>
        `;
    }
    
    monthHTML += `</div>`;
    return monthHTML;
}

document.addEventListener('DOMContentLoaded', function() {
    const tickets = @json($tickets);
    const now = new Date();
    const currentYear = now.getFullYear();
    const currentMonth = now.getMonth();
    
    let calendarHTML = '';
    
    // Render current month and next 2 months
    for (let i = 0; i < 3; i++) {
        const monthToRender = currentMonth + i;
        const yearToRender = currentYear + Math.floor(monthToRender / 12);
        const adjustedMonth = monthToRender % 12;
        
        calendarHTML += renderMonth(yearToRender, adjustedMonth, tickets, now);
    }
    
    document.getElementById('calendar').innerHTML = calendarHTML;
});
</script>
@endsection
