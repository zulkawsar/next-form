<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;
    protected $fillable     = ['user_id','name','email','phone','class','status'];
    
    const STDUNCONFIRMED    = 'unconfirmed';
    const STDADMITTED       =  'admitted';
    const STDTERMINATED     = 'terminated';

    public function studentForm()
    {
        return $this->hasMany(studentForm::class);
    }
}
