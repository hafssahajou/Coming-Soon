<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Organisateur;
use App\Models\Event;
use App\Models\Ticket;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;

class RegistredOrganisateurController extends Controller
{

    public function update(Organisateur $organisateur ,Request $request){

        $data = $request->validate([
            'status' => ['nullable'],
            
        ]);
        $organisateur->update($data);

        return redirect('/organisateur-dashboard');

    }

    // Other methods...
    public function store(Request $request): RedirectResponse
    {
        // Validate the request data
        $userdata = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'picture' => ['required'],
        ]);

        if ($request->hasFile('picture')) {
            $file = $request->file('picture');
            $pictureName = time() . '.' . $file->extension();
            $file->storeAs('public/image', $pictureName);
            $userdata['picture'] = $pictureName;
        }


        // Create a new user record
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'picture' => $userdata['picture'],
        ]);


       $organisateurData =  $request->validate([
            'phone_number' => ['required', 'string'],
            'address' => ['required', 'string'],
            'status' => ['nullable'],
           
        ]);
        $organisateurData['user_id'] = $user->id;
        
        // Create a new organisateur instance
       Organisateur::create($organisateurData);

    
        // Trigger the Registered event
        event(new Registered($user));

        // Log in the newly registered user

        // Redirect to the home page
        Session::flash('success', 'You have registered successfully! Please login to continue.');
        return redirect('/login');

        // Redirect to the login page
    }
}