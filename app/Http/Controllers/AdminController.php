<?php

namespace App\Http\Controllers;
use App\Models\Organisateur;
use App\Models\Ticket;
use App\Models\Client;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{

    public function all()
    {
        $allorganisateurs=Organisateur::get();
        $clients= Client::get();
        $reservations = Reservation::get();
        $ticketes = Ticket::get();
        $tickets = [];
        $organisateurs = [];
        $avgs = [];

        foreach ($reservations as $reservation) {
             $tickets[$reservation->id] = Ticket::where('reservation_id', $reservation->id)->get();
             $organisateurs[$reservation->id] = Organisateur::where('id', $reservation->organisateur_id)->get();

         }

        foreach($allorganisateurs as $allorganisateur){
            $avgs[$allorganisateur->id] = DB::table('reservations')->join('tickets', 'tickets.reservation_id', '=', 'reservations.id')->where('organisateur_id',$allorganisateur->id);

        }


        return view('Admin-dashboard', compact('ticketes','organisateurs','tickets','avgs','reservations','allorganisateurs','clients'));
    }


}