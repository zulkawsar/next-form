<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormField extends Model
{
    use HasFactory;
    protected $guarded = [];

    /**
     * Get the field's option.
     *
     * @param  string  $value
     * @return string
     */
    public function getOptionsAttribute($value)
    {
        return json_decode($value);
    }
}
