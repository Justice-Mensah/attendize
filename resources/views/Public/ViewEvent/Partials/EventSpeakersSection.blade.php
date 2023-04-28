<section id="speakers" class="container">
    <div class="row">
        <h1 class="section_head">
            @lang("Public_ViewEvent.speakers")
        </h1>
    </div>
    <div class="row">
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
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="alert alert-info">{{ trans('Speakers.no_speakers_found') }}</div>
    @endif
    </div>
</section>
