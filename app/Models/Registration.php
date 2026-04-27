<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
    use HasFactory;

    protected $fillable = [
        'full_name',
        'email',
        'phone',
        'gender',
        'address',
        'category',
        'year_of_study',
        'program_name',
        'program_category',
        'year_of_entry',
        'hostel_name',
        'renting_area',
        'division_of_study',
        'family',
        'wants_updates',
    ];

    protected $casts = [
        'wants_updates' => 'boolean',
    ];
}
