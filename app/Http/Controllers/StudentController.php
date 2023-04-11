<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use App\Services\StudentService;
use App\Models\{Form, FormField, Field};
use App\Http\Requests\StudentStoreRequest;
use Illuminate\Support\Facades\{Validator, DB};


class StudentController extends Controller
{
    public $studentService; 

    public function __construct()
    {
        $this->studentService = new StudentService();
    }
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
     * Store a newly created resource in storage.
     */
    public function store(StudentStoreRequest $request)
    {
        DB::transaction(function () use ($request) {
            try {
                $studentId = $this->studentService->saveStudent($request);
                $this->studentService->saveCustomData($request, $studentId);
            } catch (Exception $e) {
                $save_success = 0;
            }
        });
        alert()->success('Saved successfully');
        return back();
        
    }

    /**
     * Display the specified resource.
     */
    public function show()
    {   
        $form               = Form::where('user_id', auth()->user()->id)->first();
        $data['fields']     = $form->fields()->orderBy('form_fields.id', 'asc')->get();
        $data['students']   = Student::with('studentForm')->latest()->paginate(10);
        return view('student.show', $data);
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
