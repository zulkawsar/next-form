<?php
namespace App\Services;

use App\Models\Form;
use App\Models\Student;
use App\Models\FormField;
use App\Models\StudentForm;
/**
 * 
 */
class StudentService
{
	
	public function saveCustomData($request, int $student_id) : void
	{
		$form = Form::where('user_id', auth()->user()->id)->first();
		$field_map_ids = FormField::where('form_id', $form->id)->select('id')->pluck('id')->toArray();
		$i = 0;
		$form_data = collect($field_map_ids)->mapWithKeys(function($id) use ($request, $student_id, &$i){
		    $field_name = 'field_'. $id;
		    $data [$i] = [
		    	'student_id' => $student_id,
		    	'form_field_id' => $id,
		    	'value' => $request->input($field_name)
		    ];
		    $i++;
		    return $data;
		});
		StudentForm::insert($form_data->toArray());
	}

	public function saveStudent($request)
	{
		
		$input = $request->only('name','email','phone','class','status');
		$input['user_id'] = auth()->user()->id;
		return Student::create($input)->id;
	}
}