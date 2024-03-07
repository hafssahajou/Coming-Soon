<?php

namespace App\Http\Controllers;
use App\Models\Ticket;

class TicketController extends Controller
{
    //
    public function destroy(Request $request) {
        $ticketId = $request->input('ticket_id');
        $ticket = Ticket::findOrFail($ticketId);
        $ticket->delete();

        return redirect('/organisateur-dashboard');
    }

    public function store(Request $request){
        $data = $request->validate([
            'reservation_id' => 'required',
            'name' => 'required',
            'description' => 'required',
            'place' => 'required',
            'date' => 'required',
            'location' => 'required',
        ]);

        Ticket::create($data);
        return redirect('/organisateur-dashboard');
    }
}