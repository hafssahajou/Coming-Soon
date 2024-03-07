<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class EventController extends Controller
{
  /**
         * Remove the specified event from storage.
         *
         * @param  \Illuminate\Http\Request  $request
         * @return \Illuminate\Http\Response
         */
        public function destroy(Request $request) 
        {
            $eventId = $request->input('event_id');
            
            $event = Event::findOrFail($eventId);
            $event->delete();
    
            return redirect('/client-dashboard')->with('success', 'Event deleted successfully');
        }
       
        /**
         * Store a newly created event in storage.
         *
         * @param  \Illuminate\Http\Request  $request
         * @return \Illuminate\Http\Response
         */
        public function store(Request $request)
        {
            $data = $request->validate([
                'organisateur_id' => 'required',
                'category_id' => 'required',
                'title' => 'required',
                'description' => 'required',
                'date' => 'required|date',
                'availability_status' => 'required',
                'location' => 'required',
                'type_of_reservation' => 'required',
            ]);
    
            // Create a new event instance and save it to the database
            $event = Event::create($data);
    
            if ($event) {
                return redirect('/organisateur-dashboard')->with('success', 'Event created successfully');
            } else {
                return redirect()->back()->withInput()->withErrors(['An error occurred while creating the event']);
            }
        }
    }
    