<?php

namespace App\Http\Controllers\Api\v1\Product\Master;

use App\Actions\Product\Photo\Add;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductPhotoRequest;
use App\Models\Product\ProductMaster;
use App\Models\Product\ProductPhoto;
use Illuminate\Http\Request;

class PhotosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProductPhotoRequest $request, Add $addPhoto)
    {
        $data = $request->only('photo', 'name', 'description');

        $addPhoto->handle(ProductMaster::first(), $data);
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(ProductPhoto $productPhoto)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ProductPhoto $productPhoto)
    {
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(ProductPhoto $productPhoto)
    {
    }
}
