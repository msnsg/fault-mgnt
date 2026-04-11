<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreFaultRequest extends FormRequest
{
    /*
    * validation rules & display json on endpoint (GET Method)
    */
    public function rules()
    {
        return [
            'location.lat' => 'required|numeric|between:-90,90',
            'location.long' => 'required|numeric|between:-180,180',
            'incident_title' => 'required|string',
            'category_id' => 'required|in:1,2,3',
            'description' => 'nullable|string',
            'incident_time' => 'required|date',
            'people_involved' => 'array',
            'people_involved.*.name' => 'required_with:people_involved|string',
            'people_involved.*.type' => 'required_with:people_involved|in:staff,witness',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'status' => false,
            'message' => 'Validation failed',
            'errors' => $validator->errors(),
        ], 422));
    }
}
