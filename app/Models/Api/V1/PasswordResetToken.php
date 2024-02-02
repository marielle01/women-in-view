<?php

namespace App\Models\Api\V1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PasswordResetToken extends Model
{
    use HasFactory;
    protected $table = 'password_reset_tokens';
    protected $primaryKey = 'email';

    public $timestamps = false;
    public $incrementing = false;

    protected $fillable = [
        'email',
        'token',
    ];
}
