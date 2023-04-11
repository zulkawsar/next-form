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
                                                        <a href="javascript:void(0)" class="text-right text-info float-right" data-toggle="tooltip" data-placement="top" title="Delete"
                                                            onclick="event.preventDefault();
                                                            document.getElementById('Delete{{$field->pivot->id}}').submit();">
                                                           Remove
                                                        </a>
                                                        

                                                        @switch($field->field_type)
                                                            @case("input")
                                                                @if($options->type == 'boolean')
                                                                    <div class="form-check form-switch">
                                                                      <input class="form-check-input" type="checkbox" name="{{ $field_name }}" role="switch" id="{{ $id_for }}">
                                                                    </div>
                                                                @else 
                                                                <input type="{{ $options->type == "date" ? "text" : $options->type }}" class="form-control {{ $options->type == "date"? "datepicker" : "" }} @error($field_name) is-invalid @enderror" name={{ $field_name }} id="{{ $id_for }}" value="{{ old($field_name) }}" />
                                                                @endif
                                                            @default
                                                        @endswitch

                                                        @error($field_name)
                                                            <div class="invalid-feedback">
                                                                {{ $errors->first($field_name) }}
                                                            </div>
                                                        @enderror
                                                        
                                                        <form id="Delete{{$field->pivot->id}}" action="{{ route('field.destroy', [$field->pivot->id]) }}" method="POST" style="display: none;">
                                                            @csrf
                                                            @method('DELETE')
                                                        </form>
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