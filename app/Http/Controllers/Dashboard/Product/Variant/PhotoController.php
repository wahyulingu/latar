<?php

namespace App\Http\Controllers\Dashboard\Product\Variant;

use App\Actions\Product\Photo\Add;
use App\Actions\Product\Photo\Find;
use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Product\Photo\StoreRequest;
use App\Models\Product\ProductVariant;
use Inertia\Inertia;

class PhotoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ProductVariant $variant, Find $find)
    {
        return Inertia::render(
            component: 'Dashboard/Product/Photo/Index',
            props: [
                'photos' => $find->all($variant),
            ]
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(ProductVariant $variant)
    {
        return Inertia::render('Dashboard/Product/Photo/Index', compact('variant'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(ProductVariant $variant, StoreRequest $request, Add $create)
    {
        return $create->handle(
            $variant,
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
