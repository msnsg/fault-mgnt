<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Person extends Model
{
    protected $fillable = ['fault_id', 'name', 'type'];

    public function fault()
    {
        return $this->belongsTo(Fault::class);
    }
}