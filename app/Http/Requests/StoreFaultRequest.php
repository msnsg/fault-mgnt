<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreFaultRequest extends FormRequest
{
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
}

