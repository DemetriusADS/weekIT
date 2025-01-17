@extends('layouts.publico')

@section('content')
    <div class="m-content">
        <div class="m-portlet m-portlet--mobile">
            <div class="m-portlet__head">
                <div class="m-portlet__head-caption">
                    <div class="m-portlet__head-title">
                        <h3 class="m-portlet__head-text">
                            Cadastrar participante                            
                            <small>
                                
                            </small>                            
                        </h3>
                    </div>                    
                </div>
            </div>
            <div class="m-portlet__body">
                <div id="erro_msg" style="text-align: center !important;"></div>
                @include('layouts.partial.message')

                {!! Form::open(['method' => 'POST', 'action' => '\App\Http\Controllers\Publico\ParticipanteController@store', 'class' => 'm-form m-form--fit m-form--label-align-right m-form--state block_form_submit']) !!}

                <div class="m-form__section m-form__section--first">

                    @foreach( $model::fieldsFormCreatePublico()['fields'] as $index =>  $row)
                        <?php
                        $count = count($row);

                        if($count == 1){
                            $class_row = [
                                    'col-lg-2',
                                    'col-lg-10',
                            ];
                        }elseif($count == 2){
                            $class_row = [
                                    'col-lg-2',
                                    'col-lg-4',
                            ];
                        }elseif($count == 3){
                            $class_row = [
                                    'col-lg-2',
                                    'col-lg-2',
                            ];
                        }
                        ?>

                        <div class="form-group m-form__group row">
                            <?php
                            /** @var $value array() */
                            ?>
                            @foreach($row as $key => $value)
                                <?php
                                $class= $errors->has($key) ? "form-control m-input form-control-danger  ": 'form-control m-input ';
                                $class.= array_key_exists('class', $value) ? $value['class'] : '';
                                ?>
                                @if($value['type'] == 'form__heading')
                                    <div class="m-form__heading">
                                        <h3 class="m-form__heading-title">
                                            {{ $value['label'] }}
                                        </h3>
                                    </div>
                                    @continue
                                @else
                                    <label for="example-text-input" class="{{$class_row[0]}} col-form-label">
                                        {{$value['label'] ?? ''}}
                                    </label>
                                    <div class="{{$class_row[1]}} {{ $errors->has($key) ? 'has-danger' : '' }}">

                                        @if($value['type'] == 'date')
                                            {{ Form::date($key, old($key), ['name' => $key,'class' => $class, 'id' => $value['id'] ?? '', 'placeholder'   => $value['placeholder'] ?? '', $value['attr'] ?? '', $value['required'] ?? '']) }}
                                        @elseif($value['type'] == 'text')
                                            {{ Form::text($key, old($key), ['name' => $key,'class' => $class, 'id' => $value['id'] ?? '', 'placeholder'   => $value['placeholder'] ?? '', $value['attr'] ?? '', $value['required'] ?? '']) }}
                                        @elseif($value['type'] == 'number')
                                            {{ Form::number($key, old($key), ['name' => $key,'class' => $class, 'id' => $value['id'] ?? '', 'placeholder'   => $value['placeholder'] ?? '', $value['attr'] ?? '', $value['required'] ?? '']) }}
                                        @elseif($value['type'] == 'select')
                                            {{ Form::select($key, $value['options'], old($key), ['name' => $key,'class' => $class, 'id' => $value['id'] ?? '', 'placeholder'   => $value['placeholder'] ?? '', $value['attr'] ?? '', $value['required'] ?? '']) }}
                                        @elseif($value['type'] == 'email')
                                            {{ Form::email($key, old($key), ['name' => $key,'class' => $class, 'id' => $value['id'] ?? '', 'placeholder'   => $value['placeholder'] ?? '', $value['attr'] ?? '', $value['required'] ?? '']) }}
                                        @elseif($value['type'] == 'time')
                                            <input type="time" name="{{$key}}" class="{{ $class}}" id="{{$value['id'] ?? ''}}" placeholder="{{$value['placeholder'] ?? '' }}" {{$value['attr'] ?? ''}}  {{$value['required'] ?? '' }}>@elseif($value['type'] == 'time')
                                        @elseif($value['type'] == 'datetime-local')
                                            <input type="datetime-local"  class="{{ $class}}" name="{{$key}}" id="{{$value['id'] ?? ''}}" {{$value['attr'] ?? ''}}  {{$value['required'] ?? '' }}/>
                                        @elseif($value['type'] == 'password')
                                            {{ Form::password($key, ['name' => $key,'class' => $class, 'id' => $value['id'] ?? '', 'placeholder'   => $value['placeholder'] ?? '', $value['attr'] ?? '', $value['required'] ?? '']) }}
                                        @elseif($value['type'] == 'textarea')
                                            <textarea name="{{$key}}" class="{{$class}}" id="{{ $value['id'] ?? ''}}" placeholder="{{$value['placeholder'] ?? ''}}" {{$value['required'] ?? ''}} {{$value['attr'] ?? ''}}></textarea>
                                        @endif
                                        @if(isset($value['description']))
                                            <span class="m-form__help">
                                {{$value['description']}}
                            </span>
                                        @endif
                                        @endif
                                        @if($errors->has($key))
                                            <div class="form-control-feedback">
                                                {{$errors->first($key)}}
                                            </div>
                                        @endif
                                    </div>
                                    @endforeach
                        </div>
                    @endforeach
                </div>

                <div class="m-portlet__foot m-portlet__foot--fit">
                    <div class="m-form__actions">
                        <div class="row">
                            <div class="col-lg-2"></div>
                            <div class="col-lg-10">
                                <a href="/inscricao"><button type="button" class="btn btn-info">Voltar</button></a>
                                <button type="submit" id="bt_salvar" class="btn btn-success">
                                    Cadastrar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                {!! Form::close() !!}

            </div>
        </div>
    </div>
@endsection
