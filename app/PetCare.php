<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PetCare extends Model
{
    protected $fillable =['guest_id',
        'order_date',
        'service_id',
        'water',
        'standard_food',
        'premium_food',
        'snacks',
        'status'];

    protected $attributes
        = [
            'service_id' => 5,
        ];

    public function guest()
    {
        return $this->belongsTo(Guest::class);
    }

    /**
     * @return \___PHPSTORM_HELPERS\static|array|mixed
     */
    public static function getAllPetCareOrders()
    {
        $petCares = PetCare::all();
        if (count($petCares) > 0) {
            $serviceName = Services::getServiceName($petCares[0]->service_id);
            foreach ($petCares as $key => $petCare) {
                $petCare->serviceName = $serviceName;
                $petCare->roomNumber  = ($petCare->guest->rooms[0]->number) ? $petCare->guest->rooms[0]->number
                    : 'Pet Care id:' . $petCare->id;
            }
        } else {
            $petCares = [];
        }

        return $petCares;
    }
}
