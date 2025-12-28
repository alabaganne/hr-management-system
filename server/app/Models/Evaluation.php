<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Evaluation extends Model
{
    use HasFactory;

    protected $fillable = ['type', 'manager', 'date', 'status'];

    public function user() {
        return $this->belongsTo('App\Models\User');
    }
}
