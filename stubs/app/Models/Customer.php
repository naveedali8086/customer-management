<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'customer_code',
        'enable_notification',
        'date_of_birth',
        'gender',
        'gender_other',
        'note',
    ];

    /**
     * Get all the Customer's contacts
     */
    /*
    public function contacts(): MorphMany
    {
        return $this->morphMany(Contact::class, 'contactable');
    }
    */

    /**
     * Get all the Customer's addresses
     */
    /*
    public function addresses(): MorphMany
    {
        return $this->morphMany(Address::class, 'addressable');
    }
    */
}
