<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Form extends Model
{
    use HasFactory;

    /**
     * The fields that belong to the form.
     */
    public function fields()
    {
        return $this->belongsToMany('App\Models\Field', 'form_fields')->withPivot('id', 'options');
    }
}
