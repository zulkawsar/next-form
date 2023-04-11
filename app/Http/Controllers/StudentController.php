<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\{Form, FormField, Field};
use Illuminate\Support\Facades\{Validator, DB};


class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $form = Form::where('user_id', auth()->user()->id)->first();
        $field_map_ids = FormField::where('form_id', $form->id)->select('id')->pluck('id')->toArray();

        $data = [
            'all_fields' => Field::get(),
            'fields' => $form->fields()->orderBy('form_fields.id', 'asc')->get(),
        ];

        return view('student.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
