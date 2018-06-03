<?php

namespace App\Http\Controllers;

use App\Guest;
use App\Taxi;
use Carbon\Carbon;
use Dotenv\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;

class TaxiController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $taxis = Taxi::all();
        return view('services.taxi.index', compact('taxis'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $guests = Guest::all();
        foreach ($guests as $guest){
            $guest->guestRoomNumber = $guest->rooms[0]->number . ' - ' . $guest->firstname . ' ' . $guest->lastname;
        }
        $guests = $guests->pluck('guestRoomNumber', 'id');

        return view('services.taxi.create', compact('guests'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = Input::all();
        if ($request->ajax()){
            if (Session::exists('guest_id')){
                $input['guest_id'] = Session::get('guest_id');
            }else{
                return response()->json(['status' => false]);
            }
        }
        $rules = [
            'guest_id' => 'required|numeric',
            'day_hour' => 'required|date'
        ];
        $validator = Validator::make($input, $rules);

        if ($validator->passes()){
            try{
                DB::beginTransaction();
                $input['order_date'] = Carbon::today();
                $input['status'] = '1';
                Taxi::create($input);
                DB::commit();

                if ($request->ajax()){
                    $return = ['status' => true];
                }else{
                    $return = redirect()->route('taxi.index')->with('status', 'Order added successfully');
                }
            }catch (\Exception $e) {
                DB::rollback();
                throw $e;
            }
        }else {
            if ($request->ajax()){
                $return = ['status' => false];
            }else{
                $return = redirect()->route('restaurant.create')->withErrors($validator->getMessageBag());
            }
        }

        return $return;
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Taxi $taxi)
    {
        $guest = Guest::find($taxi->guest_id);
        return view('services.taxi.show', compact('taxi', 'guest'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Taxi $taxi)
    {
        //Pasarle el guest parar poder modificarlo
        $guests = Guest::all();

        foreach ($guests as $guest) {
            $guest->guestRoomNumber = $guest->rooms[0]->number . ' - ' . $guest->firstname . ' ' . $guest->lastname;
        }
        $guests = $guests->pluck('guestRoomNumber', 'id');

        return view('services.taxi.edit', compact('guests', 'taxi'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int                      $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $input     = Input::all();
        $rules     = [
            'day_hour' => 'required|date',
        ];

        $validator = Validator::make($input, $rules);

        if ($validator->passes()){
            try{
                DB::beginTransaction();
                $taxi = Taxi::find($id);
                $taxi->update($input);
                DB::commit();

                $return = redirect()->route('taxi.index')->with('status', 'Order updated successfully.');
            }catch (\Exception $e) {
                DB::rollback();
                throw $e;
            }
        }else{
            $return = redirect()->route('taxi.edit', $id)->withErrors($validator->getMessageBag());
        }

        return $return;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Taxi::find($id)->delete();
        return redirect()->back()->with('status', 'Order deleted successfully');
    }

    public function changeStatus($id)
    {
        $taxi         = Taxi::findOrFail($id);
        $taxi->status = ($taxi->status === '2') ? '1' : '2';
        $taxi->save();

        return response()->json($taxi->status);
    }

    /**
     * @param $id
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function changeStatus($id)
    {
        $taxi         = Taxi::findOrFail($id);
        $taxi->status = ($taxi->status === '2') ? '1' : '2';
        $taxi->save();

        return response()->json($taxi->status);
    }
}
