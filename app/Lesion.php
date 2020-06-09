<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lesion extends Model
{
    protected $table = 'lesions';

    protected $fillable = [
        'analyse', 'original_image', 'checked_image', 'classified_image', 'accuracy', 'sensitivity', 'specificity', 'dice'
    ];

    public function analyse()
    {
        return $this->belongsTo(Analyse::class, 'analyse', 'id');
    }
}
