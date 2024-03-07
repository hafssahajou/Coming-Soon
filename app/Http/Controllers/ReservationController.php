<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reservation;

class ReservationController extends Controller
{
    //
    public function destroy(Request $request) {
        $reservationId = $request->input('reservation_id');
        $reservation = Reservation::findOrFail($reservationId);
        $reservation->delete();

        return redirect('/client-dashboard');
    }
   public function store(Request $request){
    $data = $request->validate([
        'organisateur_id' => 'required',
        'event_id' => 'required',
        'client_id' => 'required',
        'time' => 'required',
        'location' => 'required',
        
    ]);



    Reservation:: create($data);
    return redirect('/organisateur-dashboard');
}
}