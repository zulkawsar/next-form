<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Validator, DB};
use App\Models\{Form, FormField, Field};

class FormBuilder extends Controller
{

    /**
     * show the form
     */
    public function showForm() {
        $form_id_toshow = 1;
        $the_form = Form::findOrFail($form_id_toshow);
        $field_map_ids = FormField::where('form_id', $form_id_toshow)->select('id')->pluck('id')->toArray();

        $data = [
            'title' => 'the form',
            'all_fields' => Field::oldest()->get(),
            'fields' => $the_form->fields()->orderBy('form_fields.id', 'asc')->get(),
            'form_id' => $form_id_toshow,
            'field_ids' => implode(",", $field_map_ids)
        ];
        // dd($data);
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

        return redirect()->back()->with('mail_sent', 1);
    }

    /**
     * handle form data ajax request save
     */
    public function saveForm(Request $request) {
        $request->validate([
            'field_type'    => 'required|exists:fields,field_type',
            'field_lable'   => 'required',
        ]);


        $fields = Field::all()->mapWithKeys(function ($field) {
            return [$field->id => $field->field_type];
        });

        // data from ajax request
        // $field_data = collect([
        //    ['field_type' => $request->field_type],
        //    ['label' => $request->field_lable],
        //    ['isRequired' => $request->is_required]
        // ]);

        switch ($request->field_type) {
            case 'input':
                $field_id = $fields->search('input');

                $additional_config = ['type' => 'text'];
                break;

            case 'select':
                $field_id = $fields->search('select');

                $values = collect($request->additionalConfig->listOptions)->map(function($opt){
                    return trim($opt);
                })->implode(',');

                $additional_config = ['values' => $values];
                break;

            case 'textarea':
                $field_id = $fields->search('textarea');

                $additional_config = ['rows' => '4'];
                break;

            case 'date':
                $field_id = $fields->search('input');
                $additional_config = ['type' => 'date'];
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
        $save_success = 1;

        DB::transaction(function () use ($config) {
            try {
                Form::findOrFail(1)->fields()->detach();

                Form::findOrFail(1)->fields()->attach($config['field'],
                [
                    'options' => json_encode($config['options'])
                ]);
            } catch (Exception $e) {
                $save_success = 0;
            }
        });

        return response()->json([
            'success' => $save_success
        ]);
    }

    // Delete form
    public function delete($id ) 
    {
        FormField::findOrFail($id)->delete();
        return back();
    }
}
