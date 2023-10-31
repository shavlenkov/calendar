<?php

namespace App\Http\Controllers;

use App\Http\Resources\RoomResource;
use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function getAll() {
        return Room::all()->map(function ($model) {
            return [
                'id' => (string)$model->id,
                'title' => $model->title
            ];
        });
    }

    public function store() {
        $room = Room::create([
            'title' => 'Машина '.(count(Room::all()) + 1)
        ]);
    }

    public function delete(Room $room) {
        $room->events->each(function ($event) {
            $event->users()->detach();
            $event->delete();
        });

        $room->delete();
    }
}
