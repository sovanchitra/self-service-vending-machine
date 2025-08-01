<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Slot extends Model
{
    protected $fillable = ['machine_id', 'slot_number'];

    protected $casts = [
        'slot_number' => 'integer',
    ];

    public function machine()
    {
        return $this->belongsTo(Machine::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}