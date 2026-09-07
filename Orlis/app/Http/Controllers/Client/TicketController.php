<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Models\TicketReply;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    public function index()
    {
        $tickets = Ticket::where('user_id', Auth::id())
                    ->with('replies.user')
                    ->orderBy('updated_at', 'desc')
                    ->get();
        return view('client.tickets.index', compact('tickets'));
    }

    public function create()
    {
        return view('client.tickets.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255', // This is now the topic
            'subject_summary' => 'required|string|max:255',
            'order_id' => 'nullable|string|max:50',
            'message' => 'required|string|min:20',
            'priority' => 'required|in:low,normal,high'
        ]);

        $fullSubject = '[' . $request->subject . '] ' . $request->subject_summary;
        if ($request->order_id) {
            $fullSubject .= ' - Mã ĐH: ' . $request->order_id;
        }

        $ticket = Ticket::create([
            'user_id' => Auth::id(),
            'subject' => $fullSubject,
            'priority' => $request->priority,
            'status' => 'open'
        ]);

        TicketReply::create([
            'ticket_id' => $ticket->id,
            'user_id' => Auth::id(),
            'message' => $request->message
        ]);

        return redirect()->route('tickets.show', $ticket)->with('success', 'Đã gửi yêu cầu hỗ trợ thành công!');
    }

    public function show(Ticket $ticket)
    {
        if ($ticket->user_id !== Auth::id()) abort(403);
        $ticket->load('replies.user');
        return view('client.tickets.show', compact('ticket'));
    }

    public function reply(Request $request, Ticket $ticket)
    {
        if ($ticket->user_id !== Auth::id()) abort(403);
        
        $request->validate(['message' => 'required|string']);

        TicketReply::create([
            'ticket_id' => $ticket->id,
            'user_id' => Auth::id(),
            'message' => $request->message
        ]);

        // Reopen if it was closed
        if ($ticket->status === 'closed') {
            $ticket->update(['status' => 'open']);
        }

        return back()->with('success', 'Đã gửi phản hồi.');
    }
}
