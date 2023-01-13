<?php

namespace App\Http\Controllers\Dashboard\Content;

use App\Actions\Content\Photo\Delete;
use App\Actions\Content\Photo\Update;
use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Content\Photo\UpdateRequest;
use App\Models\Content\ContentPhoto;
use Inertia\Inertia;

class PhotoController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(ContentPhoto::class, 'photo');
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(ContentPhoto $photo)
    {
        return Inertia::render(
            component: 'Dashboard/Content/Photo/Index',
            props: compact('photo')
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(ContentPhoto $photo)
    {
        return Inertia::render(
            component: 'Dashboard/Content/Photo/Edit',
            props: compact('photo')
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, ContentPhoto $photo, Update $update)
    {
        $update->handle($photo, $request->only(['name', 'description']));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(ContentPhoto $photo, Delete $delete)
    {
        $delete->handle($photo);
    }
}
