<?php

namespace App\Models\Api\V1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'rating',
        //'isExternal',
    ];

    protected $attributes = [
        'isExternal' => false,
    ];
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    public function movies(): BelongsTo
    {
        return $this->belongsTo(Movie::class);
    }

    public function users(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
