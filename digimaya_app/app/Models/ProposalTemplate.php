<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProposalTemplate extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'name',
        'content_blocks',
    ];

    protected $casts = [
        'content_blocks' => 'array',
    ];
}
