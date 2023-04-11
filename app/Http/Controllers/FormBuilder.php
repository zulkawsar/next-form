<?php

namespace App\Http\Controllers;

use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Models\{Form, FormField, Field};
use App\Http\Requests\FormBuilderRequest;
use Illuminate\Support\Facades\{Validator, DB};

class FormBuilder extends Controller
{

    /**
     * show the form
     */
    public function showForm() : View
    {
        $form = Form::where('user_id', auth()->user()->id)->first();
        if (!$form) {
            $form = Form::create([
                'form_name' => auth()->user()->name .' Form',
                'user_id' => auth()->user()->id
            ]);
        }
        $field_map_ids = FormField::where('form_id', $form->id)->select('id')->pluck('id')->toArray();

        $data = [
            'all_fields' => Field::get(),
            'fields' => $form->fields()->orderBy('form_fields.id', 'asc')->get(),
        ];

        return view('form.form', $data);
    }

    /**
     * handle form submit request
     */
    public function handleFormRequest(Request $request) {
        // must have data
        $validator = Validator::make($request->all(), [
            'form_id' => 'required|integer|exists:forms,id',
            'field_ids' => 'required|string',
        ]);

        abort_if($validator->fails(), 422, "Data error");

        // generating validation rules based on dynamic field config data
        $field_map_ids = explode(",", $request->input('field_ids'));

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

        // validation based on dynamic data
        $dynamic_validator = Validator::make($request->all(), $field_required_rules->toArray(), [
            'required' => "field can't be left blank",
            'email' => 'field must be a valid email address'
        ]);

        if ($dynamic_validator->fails()) {
            return redirect()->back()
                        ->withErrors($dynamic_validator)
                        ->withInput();
        }

        // gather the form data as field_name => [ label, data ]
        $form_data = collect($field_map_ids)->mapWithKeys(function($id) use ($request){
            $field_options = FormField::findOrFail($id)->options;
            $field_name = 'field_'. $id;
            return [
                $field_name => [
                    $field_options->label, $request->input($field_name)
                ]
            ];
        });
        // return new FormSubmitted($form_data);

        return redirect()->back();
    }

    /**
     * handle form data request save
     */
    public function saveForm(FormBuilderRequest $request) {
        
        $fields = Field::all()->mapWithKeys(function ($field) {
            return [$field->id => $field->field_type];
        });

        switch ($request->field_type) {
            case 'input':
                $field_id = $fields->search('input');

                $additional_config = ['type' => $request->type_option];
                break;

            default:
                $field_id = 0;
                $additional_config = [];
                break;
        }

        $common_data = [
            'label' => $request->field_lable,
            'validation' => [
                'required' => $request->isRequired ? 1 : 0
            ]
        ];

        $config = ['field' => $field_id, 'options' => array_merge($common_data, $additional_config)];

        // attempt data save
        DB::transaction(function () use ($config) {
            try {
                Form::whereUserId(auth()->user()->id)->firstOrFail()->fields()->attach($config['field'],
                [
                    'options' => json_encode($config['options'])
                ]);
            } catch (Exception $e) {
                $save_success = 0;
            }
        });

        return back();
    }

    // Delete form
    public function delete($id ) 
    {
        FormField::findOrFail($id)->delete();
        return back();
    }
}
