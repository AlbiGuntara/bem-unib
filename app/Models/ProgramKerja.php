<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Dokumentasi;

class ProgramKerja extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'description',

        'start_date',
        'end_date',

        'time',
        'location',

        'departemen_id',
        'pengurus_id',

        'participants',
        'budget',

        'realization_participants',
        'realization_budget',

        'is_public',
        'status',
    ];

    protected $casts = [
        'is_public' => 'boolean',
        'start_date' => 'date:Y-m-d',
        'end_date' => 'date:Y-m-d',
    ];

    public function department()
    {
        return $this->belongsTo(
            Department::class,
            'departemen_id'
        );
    }

    public function ketuaPanitia()
    {
        return $this->belongsTo(
            Pengurus::class,
            'pengurus_id'
        );
    }

    public function dokumentasi()
    {
        return $this->hasMany(Dokumentasi::class, 'program_kerjas_id');
    }
}