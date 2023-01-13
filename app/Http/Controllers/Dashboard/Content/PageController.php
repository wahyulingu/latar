<?php

namespace App\Http\Controllers\Dashboard\Content;

use App\Actions\Content\Page\Create;
use App\Actions\Content\Page\Delete;
use App\Actions\Content\Page\Find;
use App\Actions\Content\Page\Update;
use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Content\Page\StoreRequest;
use App\Http\Requests\Dashboard\Content\Page\UpdateRequest;
use App\Models\Content\ContentPage;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PageController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(ContentPage::class, 'page');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Find $find)
    {
        return Inertia::render(
            component: 'Dashboard/Content/Page/Index',
            props: [
                'pages' => $find->handle($request->input('query')),
            ]
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return Inertia::render('Dashboard/Content/Page/Index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request, Create $create)
    {
        return

        $create->handle(
            $request->user()->authorProfile,
            $request->only(
                keys: [
                    'title',
                    'content',
                    'description',
                    'category_id',
                ])
        );
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(ContentPage $page)
    {
        return Inertia::render(
            component: 'Dashboard/Content/Page/Index',
            props: compact('page')
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(ContentPage $page)
    {
        return Inertia::render(
            component: 'Dashboard/Content/Page/Edit',
            props: compact('page')
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, ContentPage $page, Update $update)
    {
        $update->handle($page, $request->only(
            keys: [
            'title',
            'description',
            'content',
            'category_id',
        ]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(ContentPage $page, Delete $delete)
    {
        $delete->handle($page);
    }
}
