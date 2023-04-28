<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Speaker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Image;
use App\Models\EventImage;

class EventsSpeakersController extends Controller
{
    public function index(Request $request, $event_id)
    {
        $allowed_sorts = [
            'created_at'    => trans("Controllers.sort.created_at"),
            'name'         => trans("Controllers.sort.name"),
        ];

        // Getting get parameters.
        $q = $request->get('q', '');
        $sort_by = $request->get('sort_by');
        if (isset($allowed_sorts[$sort_by]) === false) {
            $sort_by = 'sort_order';
        }
         // Find event or return 404 error.
         $event = Event::scope()->find($event_id);
         if ($event === null) {
             abort(404);
         }

        // Get Speakers for event.
        $speakers = empty($q) === false
            ? $event->speakers()->where('name', 'like', '%' . $q . '%')->paginate()
            : $event->speakers()->paginate();


        return view('ManageEvent.Speakers', compact('event', 'speakers','q', 'allowed_sorts'));

    }

    public function create($event_id)
    {
        return view('ManageEvent.Modals.CreateSpeaker', [
            'event' => Event::scope()->find($event_id),
        ]);
    }

    public function store(Request $request, $event_id)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'photo' => 'nullable|image|max:2048',
            'bio' => 'nullable|string',
        ]);

        $file_full_path = '';
        $speaker = new Speaker;
        $speaker->event_id = $event_id;
        $speaker->name = $validatedData['name'];
        $speaker->bio = $validatedData['bio'];


        if ($request->hasFile('speaker_photo')) {

            $path = public_path() . '/' . config('attendize.speakers_images_path');
            $filename = 'speaker_image-' . md5(time() . $event_id) . '.' . strtolower($request->file('speaker_photo')->getClientOriginalExtension());

            $file_full_path = $path . '/' . $filename;

            $request->file('speaker_photo')->move($path, $filename);

            $img = Image::make($file_full_path);

            $img->resize(800, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });

            $img->save($file_full_path);

            /* Upload to s3 */
            \Storage::put(config('attendize.speakers_images_path') . '/' . $filename, file_get_contents($file_full_path));

            $eventImage = EventImage::createNew();
            $eventImage->image_path = config('attendize.speakers_images_path') . '/' . $filename;
            $eventImage->event_id = $event_id;
            $eventImage->save();
        }


        $speaker->photo = config('attendize.speakers_images_path') . '/' . $filename;

        $speaker->save();


            return response()->json([
                'status'      => 'success',
                'id'          => $speaker->id,
                'message'     => trans("Controllers.refreshing"),
                'redirectUrl' => route('showEventSpeakers', [
                    'event_id' => $event_id,
                ]),
            ]);

    }

    public function edit($event_id, $speaker_id)
    {
        $data = [
            'event'  => Event::scope()->find($event_id),
            'speaker' => Speaker::find($speaker_id),
        ];

        return view('ManageEvent.Modals.EditSpeaker', $data);
    }

    public function update(Request $request, $event_id, $speaker_id)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'photo' => 'nullable|image|max:2048',
            'bio' => 'nullable|string',
        ]);
        $speaker = Speaker::findOrFail($speaker_id);

        $speaker->name = $validatedData['name'];
        $speaker->bio = $validatedData['bio'];

        if ($request->hasFile('speaker_photo')) {

            $path = public_path() . '/' . config('attendize.speakers_images_path');
            $filename = 'speaker_image-' . md5(time() . $event_id) . '.' . strtolower($request->file('speaker_photo')->getClientOriginalExtension());

            $file_full_path = $path . '/' . $filename;

            $request->file('speaker_photo')->move($path, $filename);

            $img = Image::make($file_full_path);

            $img->resize(800, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize();
            });

            $img->save($file_full_path);

            /* Upload to s3 */
            \Storage::put(config('attendize.speakers_images_path') . '/' . $filename, file_get_contents($file_full_path));

            $eventImage = EventImage::createNew();
            $eventImage->image_path = config('attendize.speakers_images_path') . '/' . $filename;
            $eventImage->event_id = $event_id;
            $eventImage->save();
        }
        $speaker->photo = config('attendize.speakers_images_path') . '/' . $filename;

        $speaker->save();

        return response()->json([
            'status'      => 'success',
            'id'          => $speaker->id,
            'message'     => trans("Controllers.refreshing"),
            'redirectUrl' => route('showEventSpeakers', [
                'event_id' => $event_id,
            ]),
        ]);
    }

    public function destroy(Request $request, $speaker_id)
    {

        $speaker = Speaker::find($speaker_id);
        if ($speaker->delete()) {
            return response()->json([
                'status'  => 'success',
                'message' => trans("Controllers.speaker_successfully_deleted"),
                'id'      => $speaker->id,
            ]);
        }

        Log::error('Speaker Failed to delete', [
            'speaker' => $speaker,
        ]);

        return response()->json([
            'status'  => 'error',
            'id'      => $speaker->id,
            'message' => trans("Controllers.whoops"),
        ]);
    }
}
