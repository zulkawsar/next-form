<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Custom Form Generate') }}
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
                                    <div class="alert alert-danger" role="alert">
                                        <strong>Error!</strong> validation error occurred
                                    </div>
                                @endif

                                @if(session('mail_sent') == 1)
                                    <div class="alert alert-success" role="alert">
                                        <strong>Success!</strong> form data sent via email
                                    </div>
                                @endif

                                <div class="card bg-light mb-3">
                                    <div class="card-body">
                                        @if(count($fields) > 0)
                                                <input type="hidden" name="form_id" value="{{ $form_id }}" />
                                                <input type="hidden" name="field_ids" value="{{ $field_ids }}" />

                                                @foreach($fields as $field)
                                                    @php
                                                        $options = $field->pivot->options? json_decode($field->pivot->options) : null;
                                                        $field_name = 'field_' . $field->pivot->id;
                                                        $id_for = 'input-fld-'. $loop->iteration;
                                                    @endphp

                                                    <div class="form-group">
                                                        @if($options->label)
                                                            <label for="{{ $id_for }}">{{ $options->label }}</label>
                                                        @endif
                                                        <a href="javascript:void(0)" class="text-right text-info float-right" data-toggle="tooltip" data-placement="top" title="Delete"
                                                            onclick="event.preventDefault();
                                                            document.getElementById('Delete{{$field->pivot->id}}').submit();">
                                                           Remove
                                                        </a>
                                                        

                                                        @switch($field->field_type)
                                                            @case("select")
                                                                <select id="{{ $id_for }}" name={{ $field_name }} class="custom-select @error($field_name) is-invalid @enderror">
                                                                    <option value="">Choose...</option>
                                                                    @foreach(explode(",", $options->values) as $value)
                                                                    <option value="{{ trim($value) }}" {{ old($field_name) == trim($value)? "selected" : "" }}>{{ trim($value) }}</option>
                                                                    @endforeach
                                                                </select>
                                                                @break

                                                            @case("textarea")
                                                                <textarea class="form-control @error($field_name) is-invalid @enderror" id="{{ $id_for }}" name={{ $field_name }} rows={{ $options->rows }}>{{ old($field_name) }}</textarea>
                                                                @break

                                                            @default
                                                                <input type="{{ $options->type == "date" ? "text" : $options->type }}" class="form-control {{ $options->type == "date"? "datepicker" : "" }} @error($field_name) is-invalid @enderror" name={{ $field_name }} id="{{ $id_for }}" value="{{ old($field_name) }}" />
                                                        @endswitch

                                                        @error($field_name)
                                                            <div class="invalid-feedback">
                                                                {{ $errors->first($field_name) }}
                                                            </div>
                                                        @enderror
                                                    </div>
                                                @endforeach
                                        @endif
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
            $('.datepicker').datepicker({
                format: 'yyyy/mm/dd'
            });
        })()
    </script>
</x-app-layout>