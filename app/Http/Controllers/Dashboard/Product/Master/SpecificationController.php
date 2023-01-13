<?php

namespace App\Http\Controllers\Dashboard\Product\Master;

use App\Actions\Product\Specification\Add;
use App\Actions\Product\Specification\Find;
use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Product\Specification\StoreRequest;
use App\Models\Product\ProductMaster;
use Inertia\Inertia;

class SpecificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ProductMaster $product, Find $find)
    {
        return Inertia::render(
            component: 'Dashboard/Product/Specification/Index',
            props: [
                'specifications' => $find->all($product),
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
        return Inertia::render('Dashboard/Product/Specification/Index', compact('product'));
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
                    'price',
                ]
            )
        );
    }
}
