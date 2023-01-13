<?php

namespace App\Http\Controllers\Dashboard\Product\Master;

use App\Actions\Product\Photo\Add;
use App\Actions\Product\Photo\Find;
use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Product\Photo\StoreRequest;
use App\Models\Product\ProductMaster;
use Inertia\Inertia;

class PhotoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ProductMaster $product, Find $find)
    {
        return Inertia::render(
            component: 'Dashboard/Product/Photo/Index',
            props: [
                'photos' => $find->all($product),
            ]
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(ProductMaster $product)
    {
        return Inertia::render('Dashboard/Product/Photo/Index', compact('product'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(ProductMaster $product, StoreRequest $request, Add $create)
    {
        return $create->handle(
            $product,
            $request->only(
                [
                    'name',
                    'description',
                    'photo',
                ]
            )
        );
    }
}
