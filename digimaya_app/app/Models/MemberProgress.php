<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MemberProgress extends Model
{
    use HasFactory;

    protected $table = 'member_progress';

    protected $fillable = [
        'member_id',
        'material_id',
        'completed_at',
    ];

    protected $casts = [
        'member_id' => 'integer',
        'material_id' => 'integer',
        'completed_at' => 'datetime',
    ];

    // ====================
    // Relations
    // ====================

    public function member()
    {
        return $this->belongsTo(Member::class);
    }

    public function material()
    {
        return $this->belongsTo(Material::class);
    }
}
