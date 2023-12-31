<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    use HasFactory;

    public const GENDER = ['Male', 'Female'];
    protected $table = 'customers';
    protected $fillable = [
        'name',
        'gender',
        'birth_date',
        'phone',
        'email',
        'count_transaction',
        'last_transaction_id',
        'last_order_id',
        'is_active'
    ];

    public function consulations(): HasMany
    {
        return $this->hasMany(Consultation::class);
    }

    public function scopeIsActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    // public function treatments(): HasMany
    // {
    //     return $this->hasMany(TreatmentPatient::class, 'patient_id', 'id');
    // }
}
