<?php 

namespace App\Traits;

use App\Models\Form;
use App\Models\FormField;

trait CustomFieldValidation 
{
	/* 
	Generate rules base on user custom field
	*/
	protected function generateValidateRules()
	{
		$form = Form::where('user_id', auth()->user()->id)->first();
		$field_map_ids = FormField::where('form_id', $form->id)->select('id')->pluck('id')->toArray();

		// generating validation rules based on dynamic field config data
		$field_required_rules = collect($field_map_ids)->mapWithKeys(function($id){
		    $field_options = FormField::findOrFail($id)->options;
		    if($field_options->validation->required == 1) {
		        $rules = ['required'];
		        if(isset($field_options->type) && $field_options->type == "email") $rules[] = 'email';

		        return [
		            'field_'. $id => implode("|", $rules)
		        ];
		    }
		    else {
		        return ['field_'. $id => 'nullable'];
		    }
		});

		return $field_required_rules->toArray();
	}
}