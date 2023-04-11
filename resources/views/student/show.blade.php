<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Student List') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="section mb-5">

                        <div class="row">
                            <table class="table table-bordered">
                              <thead>
                                <tr>
                                    <th scope="col">#</th>
                                    <th scope="col">Name</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Phone</th>
                                    <th scope="col">Class</th>
                                    <th scope="col">Status</th>
                                    @foreach($fields as $field)
                                        @php
                                            $options = $field->pivot->options? json_decode($field->pivot->options) : null;
                                        @endphp  
                                          <th scope="col">{{ $options->label }}</th>
                                    @endforeach
                                </tr>
                              </thead>
                              <tbody>
                                @foreach($students as $student)
                                <tr>
                                    <th scope="row"></th>
                                    <td>{{ $student->name }}</td>
                                    <td>{{ $student->email }}</td>
                                    <td>{{ $student->phone }}</td>
                                    <td>{{ $student->class }}</td>
                                    <td><span class="badge text-bg-@if($student->status == 'unconfirmed')info @elseif($student->status == 'admitted')success @else danger @endif ">{{ $student->status }}</span></td>
                                    @foreach($student->studentForm as $sform)
                                        @foreach($fields as $field)
                                            @if($field->pivot->id == $sform->form_field_id)
                                            <td>{{ $sform->value }}</td>
                                            @endif
                                        @endforeach
                                    @endforeach
                                </tr>
                                @endforeach
                              </tbody>
                            </table>
                        </div>
                        {{$students->links()}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>