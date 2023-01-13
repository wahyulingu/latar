<?php

namespace App\Http\Controllers\Dashboard\Product\Variant;

use App\Actions\Product\Video\Add;
use App\Actions\Product\Video\Find;
use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Product\Video\StoreRequest;
use App\Models\Product\ProductVariant;
use Inertia\Inertia;

class VideoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ProductVariant $variant, Find $find)
    {
        return Inertia::render(
            component: 'Dashboard/Product/Video/Index',
            props: [
                'videos' => $find->all($variant),
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
        return Inertia::render('Dashboard/Product/Video/Index', compact('variant'));
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
                    'video',
                ]
            )
        );
    }
}
