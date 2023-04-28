<div role="dialog"  class="modal fade" style="display: none;">

    {{-- @include('ManageOrganiser.Partials.EventCreateAndEditJS'); --}}

    {!! Form::open(array('url' => route('postCreateSpeakers'), 'class' => 'ajax gf')) !!}
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header text-center">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h3 class="modal-title">
                    <i class="ico-calendar"></i>
                    @lang("Event.create_event")</h3>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            {!! Form::label('name', trans("Event.speaker"), array('class'=>'control-label required')) !!}
                            {!!  Form::text('name', old('name'),array('class'=>'form-control','placeholder'=>trans("Speaker.name", ["name"=>Auth::user()->first_name]) ))  !!}
                        </div>

                        <div class="form-group custom-theme">
                            {!! Form::label('bio', trans("Speaker.bio"), array('class'=>'control-label required')) !!}
                            {!!  Form::textarea('bio', old('bio'),
                                        array(
                                        'class'=>'form-control  editable',
                                        'rows' => 5
                                        ))  !!}
                        </div>

                        <div class="form-group">
                            {!! Form::label('speaker_photo', trans("Speaker.photo"), array('class'=>'control-label ')) !!}
                            {!! Form::styledFile('speaker_photo') !!}

                        </div>

                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <span class="uploadProgress"></span>
                {!! Form::button(trans("basic.cancel"), ['class'=>"btn modal-close btn-danger",'data-dismiss'=>'modal']) !!}
                {!! Form::submit(trans("Speaker.create_speaker"), ['class'=>"btn btn-success"]) !!}
            </div>
        </div>
        {!! Form::close() !!}
    </div>
</div>
