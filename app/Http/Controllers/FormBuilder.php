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
                'required' => $request->is_required ? 1 : 0
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
