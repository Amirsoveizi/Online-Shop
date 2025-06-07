<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'apartment',
        'street',
        'area',
        'city',
        'state',
        'postal_code',
        'country',
        'label',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
