<?php

namespace App\Http\Controllers\Dashboard\Content;

use App\Actions\Content\Video\Delete;
use App\Actions\Content\Video\Update;
use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Content\Video\UpdateRequest;
use App\Models\Content\ContentVideo;
use Inertia\Inertia;

class VideoController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(ContentVideo::class, 'video');
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(ContentVideo $video)
    {
        return Inertia::render(
            component: 'Dashboard/Content/Video/Index',
            props: compact('video')
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(ContentVideo $video)
    {
        return Inertia::render(
            component: 'Dashboard/Content/Video/Edit',
            props: compact('video')
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, ContentVideo $video, Update $update)
    {
        $update->handle($video, $request->only(['name', 'description']));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(ContentVideo $video, Delete $delete)
    {
        $delete->handle($video);
    }
}
