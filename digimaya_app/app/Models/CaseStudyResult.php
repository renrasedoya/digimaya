<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CaseStudyResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'case_study_id',
        'value',
        'label',
        'position_order',
    ];

    protected $casts = [
        'position_order' => 'integer',
    ];

    public function caseStudy()
    {
        return $this->belongsTo(CaseStudy::class);
    }
}
