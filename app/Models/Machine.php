<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Machine extends Model
{
    protected $fillable = [
        'location',
        'status', // active, inactive
    ];
    protected $casts = [
        'status' => 'string',
    ];
    public function employees()
    {
        return $this->belongsToMany(Employee::class, 'machine_employee');
    }
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}