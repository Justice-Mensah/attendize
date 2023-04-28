@extends('Shared.Layouts.Master')

@section('title')
    @parent
    @lang("Ticket.event_tickets")
@stop

@section('top_nav')
    @include('ManageEvent.Partials.TopNav')
@stop

@section('page_title')
    <i class="ico-ticket mr5"></i>
    @lang("Speakers.event_speakers")
@stop

@section('menu')
    @include('ManageEvent.Partials.Sidebar')
@stop

@section('page_header')
    <div class="col-md-9">
        <!-- Toolbar -->
        <div class="btn-toolbar" role="toolbar">
            <div class="btn-group btn-group-responsive">
                <button data-modal-id='CreateSpeaker'
                        data-href="{{route('showCreateSpeaker', array('event_id'=>$event->id))}}"
                        class='loadModal btn btn-success' type="button"><i class="ico-ticket"></i> @lang("Speakers.create_speaker")
                </button>
            </div>

        </div>
        <!--/ Toolbar -->
    </div>
    <div class="col-md-3">
        {!! Form::open(array('url' => route('showEventSpeakers', ['event_id'=>$event->id]), 'method' => 'get')) !!}
        <div class="input-group">
            <input name='q' value="{{$q or ''}}" placeholder="@lang("Speakers.search_speakers")" type="text" class="form-control">
        <span class="input-group-btn">
            <button class="btn btn-default" type="submit"><i class="ico-search"></i></button>
        </span>
        </div>
        {!! Form::close() !!}
    </div>
@stop
@section('content')
@if($speakers->count())
<div class="row">
    <div class="col-md-3 col-xs-6">
        <div class='order_options'>
            <span class="event_count">@lang("Speakers.n_speakers", ["num"=>$speakers->count()])</span>
        </div>
    </div>

</div>
@endif

    @if($speakers->count())
        <div class="row">
            @foreach($speakers as $speaker)
                <div class="col-md-4">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">{{ $speaker->name }}</h3>
                        </div>
                        <div class="panel-body">
                            <img src="{{config('attendize.cdn_url_user_assets').'/'.$speaker->photo}}" alt="{{ $speaker->name }}" class="img-responsive">
                            <p>{{ $speaker->bio }}</p>
                        </div>
                        <div class="panel-footer">
                            <ul class="nav nav-section nav-justified">
                                <li>

                                    <button data-modal-id='CreateSpeaker'
                                    data-href="{{ route('showEditSpeaker', ['event_id' => $event->id, 'speaker_id' => $speaker->id]) }}"
                        class='loadModal btn btn-success' type="button"><i class="ico-edit"></i> @lang("basic.edit")
                </button>

                                </li>

                                <li>
                                    <a href="{{route('postDeleteSpeaker', ['speaker_id' => $event->id])}}">
                                        <i class="ico-bin"></i> @lang("basic.delete")
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>

                </div>
            @endforeach
        </div>
    @else
        <div class="alert alert-info">{{ trans('Speakers.no_speakers_found') }}</div>
    @endif
@stop
