<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\TicketReply;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status', 'open');
        $tickets = Ticket::with('user')->where('status', $status)->orderBy('created_at', 'desc')->paginate(15);
        
        $openCount = Ticket::where('status', 'open')->count();
        $closedCount = Ticket::where('status', 'closed')->count();

        return view('admin.tickets.index', compact('tickets', 'openCount', 'closedCount', 'status'));
    }

    public function show(Ticket $ticket)
    {
        $ticket->load('replies.user');
        return view('admin.tickets.show', compact('ticket'));
    }

    public function reply(Request $request, Ticket $ticket)
    {
        $request->validate(['message' => 'required|string']);

        TicketReply::create([
            'ticket_id' => $ticket->id,
            'user_id' => Auth::id(), // Admin ID
            'message' => $request->message
        ]);

        return back()->with('success', 'Đã phản hồi khách hàng.');
    }

    public function close(Ticket $ticket)
    {
        $ticket->update(['status' => 'closed']);
        return back()->with('success', 'Đã đóng ticket này.');
    }
}
