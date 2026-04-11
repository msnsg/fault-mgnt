<?php

namespace App\Http\Controllers;


use App\Models\Fault;
use Illuminate\Http\Request;
//use App\Http\Requests\StoreFaultRequest;
use Illuminate\Support\Facades\Validator;

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
    public function store(Request $request)
    {  

        if (!$request->isJson()) {
            return response()->json([
                'message' => 'Invalid content type. JSON required.'
            ], 400);
        }

        $validator = Validator::make($request->all(), [
            'location.lat' => 'required|numeric|between:-90,90',
            'location.long' => 'required|numeric|between:-180,180',
            'incident_title' => 'required|string',
            'category_id' => 'required|in:1,2,3',
            'description' => 'nullable|string',
            'incident_time' => 'required|date',
            'people_involved' => 'array',
            'people_involved.*.name' => 'required_with:people_involved|string',
            'people_involved.*.type' => 'required_with:people_involved|in:staff,witness',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 422,
                'message' => 'Validation failure — one or more input fields are invalid or missing',
                'errors' => $validator->errors()
            ], 422);
        }
        
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

