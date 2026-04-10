<?php

namespace App\Http\Controllers;


use App\Models\Fault;
use App\Http\Requests\StoreFaultRequest;

class FaultController extends Controller
{
    /*
    * To Get all fault items list
    */
    public function index()
    {
        $faults = Fault::with(['persons', 'category'])->orderBy('id')->paginate(1);
        return response()->json($faults, 200, [], JSON_PRETTY_PRINT);
        //return view('faults.index', compact('faults'));
    }

    /*
    * To store fault items
    */
    public function store(StoreFaultRequest $request)
    {
        $data = $request->validated();

        $fault = Fault::create([
            'fault_reference' => Fault::generateReference(),
            'incident_title' => $data['incident_title'],
            'category_id' => $data['category_id'],
            'lat' => $data['location']['lat'],
            'long' => $data['location']['long'],
            'description' => $data['description'] ?? null,
            'incident_time' => $data['incident_time'],
        ]);

        if (!empty($data['people_involved'])) {
            foreach ($data['people_involved'] as $person) {
                $fault->persons()->create($person);
            }
        }

        if ($fault->save()) {
            return response()->json($fault, 201);
        } else {
            return response()->json(['error' => 'Failed to save'], 500);
        }
    }
}

