<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */
    protected $fillable = [
        'transaction_code',
        'employee_id',
        'slot_id',
        'point',
        'status',
        'machine_location',
        'notes',
    ];
    
    protected $casts = [
        'point' => 'integer',
        'status' => 'string',
        'machine_location' => 'string',
        'notes' => 'string',
        'transaction_code' => 'string',
        'employee_id' => 'integer',
        'slot_id' => 'integer',
    ];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */
    public function slot()
    {
        return $this->belongsTo(Slot::class);
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function card()
    {
        return $this->belongsTo(Card::class);
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
