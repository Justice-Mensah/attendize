<div role="dialog"  class="modal fade " style="display: none;">
    {!! Form::model($speaker, ['url' => route('postEditSpeaker', ['speaker_id' => $speaker->id, 'event_id' => $event->id]), 'class' => 'ajax']) !!}
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header text-center">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <h3 class="modal-title">
                    Edit Speaker</h3>
            </div>
            <div class="modal-body">

                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            {!! Form::label('name', trans("Event.speaker"), array('class'=>'control-label required')) !!}
                            {!!  Form::text('name', old('name'),array('class'=>'form-control','placeholder'=>trans("Speakers.name", ) ))  !!}
                        </div>

                        <div class="form-group custom-theme">
                            {!! Form::label('bio', trans("Speakers.bio"), array('class'=>'control-label required')) !!}
                            {!!  Form::textarea('bio', old('bio'),
                                        array(
                                        'class'=>'form-control  editable',
                                        'rows' => 5
                                        ))  !!}
                        </div>

                        <div class="form-group">
                            {!! Form::label('speaker_photo', trans("Speakers.photo"), array('class'=>'control-label ')) !!}
                            {!! Form::styledFile('speaker_photo', ['onchange' => 'previewSpeakerImage(this);']) !!}
                        </div>

                    </div>
                </div>
            </div> <!-- /end modal body-->
            <div class="modal-footer">
                {!! Form::button(trans("basic.cancel"), ['class'=>"btn modal-close btn-danger",'data-dismiss'=>'modal']) !!}
                {!! Form::submit('Update Speaker', ['class'=>"btn btn-success"]) !!}
            </div>
        </div><!-- /end modal content-->
       {!! Form::close() !!}
    </div>
</div>
@section('head')
    <script>
        function previewSpeakerImage(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $('#speaker-image-preview').attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
@stop
