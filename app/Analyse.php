<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Analyse extends Model
{
    protected $table = 'analyses';

    protected $fillable = [
        'user', 'title', 'description'
    ];

    public function patient()
    {
        return $this->belongsTo(User::class, 'user', 'id');
    }

    public function lesion()
    {
        return $this->hasMany(Lesion::class, 'analyse', 'id');
    }
}
