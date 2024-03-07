<?php
use App\Models\Organisateur;

use App\Models\Ticket;
use App\Models\Client;
use App\Models\Reservation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\RegisteredOrganisateurController;


Route::get('/', function () {
    return view('welcome');
});
Route::get('/login', function () {
    return view('auth.login');})->name('llogin');;
;

Route::get('/register', function () {
    return view('auth.register');
});
Route::get('/client-dashboard', function () {
    return view('client-dashboard');
});
Route::get('/admin-dashboard', function () {
    return view('admin-dashboard');
})->name('admin-dashboard'); 

Route::post('/register', [RegisteredUserController::class, 'store'])->name('register');

Route::get('/organisateurregister', function () {
    return view('auth.organisateur-register');
});



Route::get('/admin-dashboard', [AdminController::class, 'all'])->name('admin-dashboard');

Route::put('/organisateur/{organisateur}', [RegisteredOrganisateurController::class, 'update'])->name('organisateur.update');

Route::post('/organisateur-register', [RegisteredOrganisateurController::class, 'store'])->name('organisateur-register');

Route::post('/reservation/store',[ReservationController::class,'store'])->name('reservation.store');
Route::post('/ticket/store',[TicketController::class,'store'])->name('ticket.store');


Route::middleware(['auth','verified'])->group(function () {
    Route::get('/organisateur-dashboard', function () {
        // Retrieve the organisateur information based on the logged-in user's ID

        $organisateur = Organisateur::where('user_id', Auth::id())->first();
        if ($organisateur->banned == 1 ) {
            return redirect()->route('llogin')->with('error', 'please contact us if you think we made a mistake.');
        }
        if (!$organisateur) {
            // Handle the case where the organisateur doesn't exist
            return redirect()->route('llogin')->with('error', 'organisateur information not found.');
        }
        $reservations = Reservation::where('organisateur_id', $organisateur->id)->get();
        $clients = [];
        $tickets = [];
        $avg = DB::table('reservations')->join('tickets', 'tickets.reservation_id', '=', 'reservations.id')->where('organisateur_id',$organisateur->id);

       foreach ($reservations as $reservation) {
            $tickets[$reservation->id] = Ticket::where('reservation_id', $reservation->id)->get();
            $clients[$reservation->id] = Client::where('id', $reservation->client_id)->get();

        }

        return view('organisateur-dashboard', compact('organisateur', 'reservations', 'tickets', 'clients','avg'));
    })->name('organisateur-dashboard');


    Route::delete('/client-dashboard', [ReservationController::class,'destroy'])->name('reservation.delete');
    Route::post('/client-dashboard', [RegisteredUserController::class,'filter'])->name('organisateur-filtre');
    Route::get('/client-dashboard', function () {
            // Retrieve the organisateur information based on the logged-in user's ID
            $client= Client::where('user_id', Auth::id())->first();
            if ($client ) {
                return redirect()->route('llogin')->with('error', 'please contact us if you think we made a mistake.');
            }
            $reservations = Reservation::where('client_id', $client->id,'deleted',0)->get();
            $allorganisateurs=Organisateur::get();
            // where('banned', 0)->
            $organisateurs = [];
            $tickets = [];
            $avgs=[];

           foreach ($reservations as $reservation) {
                $tickets[$reservation->id] = Ticket::where('reservation_id', $reservation->id)->get();
                $organisateurs[$reservation->id] = Organisateur::where('id', $reservation->organisateur_id)->get();

            }
            foreach($allorganisateurs as $allorganisateur){
                $avgs[$allorganisateur->id] = DB::table('reservations')->join('tickets', 'tickets.reservation_id', '=', 'reservations.id')->where('organisateur_id',$allorganisateur->id);

            }

            return view('client-dashboard', compact( 'client','organisateurs','allorganisateurs', 'tickets','reservations','avgs'));
        })->name('client-dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';