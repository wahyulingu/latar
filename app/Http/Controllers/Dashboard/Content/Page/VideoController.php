<?php

namespace App\Http\Controllers\Dashboard\Content\Page;

use App\Actions\Content\Video\Add;
use App\Actions\Content\Video\Find;
use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Content\Video\StoreRequest;
use App\Models\Content\ContentPage;
use Inertia\Inertia;

class VideoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ContentPage $page, Find $find)
    {
        return Inertia::render(
            component: 'Dashboard/Content/Video/Index',
            props: [
                'videos' => $find->all($page),
            ]
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(ContentPage $page)
    {
        return Inertia::render('Dashboard/Content/Video/Index', compact('page'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(ContentPage $page, StoreRequest $request, Add $create)
    {
        return $create->handle(
            $page,
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
