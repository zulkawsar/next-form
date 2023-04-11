<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Student Form') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="section mb-5">
                        <div class="row">
                            <div class="col-sm-12 col-md-8 offset-md-2">
                                @if($errors->any())
                                    @foreach ($errors->all() as $message) 
                                        <div class="alert alert-danger" role="alert">
                                            {{$message}}
                                        </div>
                                    @endforeach
                                @endif

                                <div class="card bg-light mb-3">
                                    <div class="card-body">
                                        <form id="studentForm" method="post" action="{{ route('student.save') }}" role="form" data-toggle="validator">
                                            @csrf
                                            <div class="mb-3">
                                                <label for="class">Class Name</label>
                                                <input type="text" class="form-control" name="class" placeholder="Six" id="class" value="{{ old('class') }}"  required/>
                                                <div class="help-block with-errors"></div>
                                            </div>
                                            <div class="mb-3">
                                                <label for="name">Name</label>
                                                <input type="text" class="form-control" name="name" id="name" placeholder="Jon Doe" value="{{ old('name') }}"  required/>
                                                <div class="help-block with-errors"></div>
                                            </div>
                                            
                                            <div class="mb-3">
                                                <label for="email">Email</label>
                                                <input type="email" class="form-control" name="email" id="email" placeholder="example@mail.com" value="{{ old('email') }}"  required/>
                                                <div class="help-block with-errors"></div>
                                            </div>
                                            <div class="mb-3">
                                                <label for="phone_number">Phone number:</label>
                                                <input type="text" id="phone_number" name="phone" class="form-control" placeholder="+880XXXXXXXXXX" maxlength="13" value="{{ old('phone') }}" required>
                                                <div class="help-block with-errors"></div>
                                            </div>
                                            <div class="mb-3">
                                                <label for="phone_number">Status</label>
                                                <select class="form-select" name="status" aria-label="Default select status" required>
                                                  <option selected>Select one</option>
                                                  <option value="unconfirmed">Unconfirmed</option>
                                                  <option value="admitted">Admitted</option>
                                                  <option value="admitted">Admitted</option>
                                                </select>
                                                <div class="help-block with-errors"></div>
                                            </div>

                                            @if(count($fields) > 0)

                                                @foreach($fields as $field)
                                                    @php
                                                        $options = $field->pivot->options? json_decode($field->pivot->options) : null;
                                                        $field_name = 'field_' . $field->pivot->id;
                                                        $id_for = 'input-fld-'. $loop->iteration;
                                                    @endphp

                                                    <div class="mb-3">
                                                        @if($options->label)
                                                            <label for="{{ $id_for }}">{{ $options->label }}</label>
                                                        @endif

                                                        @switch($field->field_type)
                                                            @case("input")
                                                                @if($options->type == 'boolean')
                                                                    <div class="form-check form-switch">
                                                                      <input class="form-check-input" type="checkbox" name="{{ $field_name }}" role="switch" id="{{ $id_for }}" {{$options->validation->required ? 'required' : ''}}>
                                                                    </div>
                                                                @else 
                                                                <input type="{{ $options->type == "date" ? "text" : $options->type }}" class="form-control {{ $options->type == "date"? "datepicker" : "" }} @error($field_name) is-invalid @enderror" name={{ $field_name }} id="{{ $id_for }}" value="{{ old($field_name) }}" {{$options->validation->required ? 'required' : ''}} />
                                                                @endif
                                                            @default
                                                        @endswitch

                                                        @error($field_name)
                                                            <div class="invalid-feedback">
                                                                {{ $errors->first($field_name) }}
                                                            </div>
                                                        @enderror
                                                        <div class="help-block with-errors"></div>
                                                    </div>

                                                @endforeach
                                            @endif

                                            <button type="submit" class="btn btn-info bg-info">Save</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        (function(){
            $('#studentForm').validate()
            $('.datepicker').datepicker({
                format: 'yyyy/mm/dd'
            });
        })()
    </script>
</x-app-layout>