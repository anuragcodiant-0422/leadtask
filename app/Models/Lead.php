<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    use HasFactory;
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'company_name',
        'source',
        'change_status',
        'assigned_to',
        'notes',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'assigned_to', 'id');
    }
}
