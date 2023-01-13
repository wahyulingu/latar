<?php

namespace App\Http\Controllers\Dashboard\Product;

use App\Actions\Product\Photo\Delete;
use App\Actions\Product\Photo\Update;
use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Product\Photo\UpdateRequest;
use App\Models\Product\ProductPhoto;
use Inertia\Inertia;

class PhotoController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(ProductPhoto::class, 'photo');
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(ProductPhoto $photo)
    {
        return Inertia::render(
            component: 'Dashboard/Product/Photo/Index',
            props: compact('photo')
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(ProductPhoto $photo)
    {
        return Inertia::render(
            component: 'Dashboard/Product/Photo/Edit',
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
    public function update(UpdateRequest $request, ProductPhoto $photo, Update $update)
    {
        $update->handle($photo, $request->only(['name', 'description']));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProductPhoto $photo, Delete $delete)
    {
        $delete->handle($photo);
    }
}
