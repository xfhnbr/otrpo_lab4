<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Popover extends Model
{
    use HasFactory;

    protected $fillable = [
        'museum_id',
        'target_text',
        'title',
        'content',
        'position'
    ];

    public function museum()
    {
        return $this->belongsTo(Museum::class);
    }
}