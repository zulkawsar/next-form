<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#create" data-bs-whatever="@mdo">Open modal for @mdo</button>
                    
                    <div class="modal fade" id="create" tabindex="-1" aria-labelledby="createLabel" aria-hidden="true">
                      <div class="modal-dialog">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h1 class="modal-title fs-5" id="createLabel">Add Custom Field</h1>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                          </div>
                          <div class="modal-body">
                            <form method="POST" action="{{ route('save.form') }}">
                                @csrf
                              <div class="mb-3">
                                <label for="select-name" class="col-form-label">Select type</label>
                                <select class="form-select" name="field_type">
                                  <option value="">Select one</option>
                                  @foreach($all_fields as $field)
                                      <option value="{{$field->field_type}}">{{$field->field_type}}</option>
                                  @endforeach
                                </select>
                                
                              </div>
                              <div class="mb-3">
                                <label for="label-name" class="col-form-label">Label name</label>
                                <input type="text" class="form-control" name="field_lable" id="label-name">
                              </div>
                              <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_required" role="switch" id="isRequired">
                                <label class="form-check-label" for="isRequired">Required</label>
                              </div>

                              <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Create</button>
                              </div>
                            </form>
                          </div>
                        </div>
                      </div>
                    </div>


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
                                                        <a class="btn btn-success btn-sm btn-xs text-white" data-toggle="tooltip" data-placement="top" title="Delete"
                                                            onclick="event.preventDefault();
                                                            document.getElementById('Delete{{$field->pivot->id}}').submit();">
                                                           <i class="fa fa-paper-plane"></i>
                                                        </a>
                                                        
                                                        <form id="Delete{{$field->pivot->id}}" action="{{ route('field.destroy', [$field->pivot->id]) }}" method="POST" style="display: none;">
                                                            @csrf
                                                            @method('DELETE')
                                                        </form>
                                                    </div>
                                                @endforeach

                                                <div class="form-group mt-4">
                                                    <button type="submit" class="btn btn-primary">
                                                        <i class="fa fa-paper-plane-o" aria-hidden="true"></i> Submit
                                                    </button>
                                                </div>
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
</x-app-layout>