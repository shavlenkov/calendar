<?php

namespace App\Http\Controllers;

use App\Http\Resources\EventResource;
use App\Models\Event;
use App\Models\User;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function getAll() {
        return EventResource::collection(Event::all());
    }

    public function findOne(Event $event) {
        return new EventResource($event);
    }

    public function store(Request $request) {
        $event = Event::create([
            'title' => $request->title,
            'resourceId' => $request->resource_id,
            'format' => $request->format2,
            'start' => $request->start,
            'end' => $request->end
        ]);

        $films = User::whereIn('name', $request->users)->get();

        $event->users()->attach($films);
    }

    public function edit(Event $event, Request $request) {
        $event->start = $request->start;
        $event->end = $request->end;
        $event->resourceId = $request->resourceId;

        $event->save();
    }

    public function join(Request $request) {
        $event = Event::find($request->event_id);

        $event->users()->attach($request->user_id);

        return 'ok';
    }

    public function unjoin(Request $request) {
        $event = Event::find($request->event_id);

        $event->users()->detach($request->user_id);

        return 'ok';
    }
}
