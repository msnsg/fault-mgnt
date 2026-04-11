<?php

namespace App\Models;
use App\Models\Category;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Fault extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'fault_reference',
        'incident_title',
        'category_id',
        'lat',
        'long',
        'name',
        'type',
        'description',
        'incident_time',
    ];

    protected $casts = [
        'incident_time' => 'datetime',
    ];

    public function persons()
    {
        return $this->hasMany(Person::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }


    public static function generateReference()
    {
        /*
        * Reference ID auto increment based on latest date
        */
        return \DB::transaction(function () {
            $date = now()->format('Ymd');

            $last = self::whereDate('created_at', now())
                ->lockForUpdate()
                ->orderBy('fault_reference', 'desc')
                ->first();

            $sequence = $last
                ? (int) substr($last->fault_reference, -4) + 1
                : 1;

            return $date . str_pad($sequence, 4, '0', STR_PAD_LEFT);
        });
    }

}
