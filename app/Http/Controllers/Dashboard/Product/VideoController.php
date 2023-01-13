<?php

namespace App\Http\Controllers\Dashboard\Product;

use App\Actions\Product\Video\Delete;
use App\Actions\Product\Video\Update;
use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Product\Video\UpdateRequest;
use App\Models\Product\ProductVideo;
use Inertia\Inertia;

class VideoController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(ProductVideo::class, 'video');
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(ProductVideo $video)
    {
        return Inertia::render(
            component: 'Dashboard/Product/Video/Index',
            props: compact('video')
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(ProductVideo $video)
    {
        return Inertia::render(
            component: 'Dashboard/Product/Video/Edit',
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
    public function update(UpdateRequest $request, ProductVideo $video, Update $update)
    {
        $update->handle($video, $request->only(['name', 'description']));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProductVideo $video, Delete $delete)
    {
        $delete->handle($video);
    }
}
