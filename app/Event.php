<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{

    protected $fillable
        = [
            'guest_id',
            'event_type_id',
            'order_date',
            'people_num',
            'status',
        ];

    protected $attributes
        = [
            'service_id' => 7,
        ];

    public function eventType()
    {
        return $this->belongsTo(EventType::class);
    }

    public function guest()
    {
        return $this->belongsTo(Guest::class);
    }

    public static function getAllEventOrders()
    {
        $events = Event::all();
        if (count($events) > 0) {
            $serviceName = Services::getServiceName($events[0]->service_id);
            foreach ($events as $key => $event) {
                $event->serviceName = $serviceName;
                $event->roomNumber  = ($event->guest->rooms[0]->number) ? $event->guest->rooms[0]->number
                    : 'Event id:' . $event->id;
            }
        } else {
            $events = [];
        }

        return $events;
    }
}
