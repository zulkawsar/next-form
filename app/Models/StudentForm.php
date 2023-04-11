<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentForm extends Model
{
    use HasFactory;
    protected $fillable = ['student_id','form_field_id','value'];

    
}
