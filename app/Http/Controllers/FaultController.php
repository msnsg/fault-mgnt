<?php

namespace App\Http\Controllers;


use App\Models\Fault;
use App\Http\Requests\StoreFaultRequest;

class FaultController extends Controller
{
    /*
    * To Get all the fault items list
    */
    public function index()
    {
        $faults = Fault::with(['persons', 'category'])->orderBy('id')->paginate(10);
        return response()->json($faults, 200, [], JSON_PRETTY_PRINT);
        #return view('faults.index', compact('faults')); //for view 
    }

    /*
    * To store fault item with required data
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

        try {
    $fault->save();

    return response()->json([
        'status' => true,
        'message' => 'Fault created successfully',
        'data' => $fault
    ], 201);

} catch (\Exception $e) {
    return response()->json([
        'status' => false,
        'message' => 'Internal server error'
    ], 500);
}

        if ($fault->save()) {
            return response()->json([
                'status' => true,
                'message' => 'Fault created successfully',
                'data' => $fault
            ],201);
        } else {
            return response()->json(['error' => 'Failed to save'], 500);
        }

        // if ($fault->save()) {
        //      return response()->json($fault, 201);
        // } else {
        //     return response()->json(['error' => 'Failed to save'], 500);
        // }
    }
}

