<?php

namespace App\Http\Controllers\Dashboard\Content;

use App\Actions\Content\Article\Create;
use App\Actions\Content\Article\Delete;
use App\Actions\Content\Article\Find;
use App\Actions\Content\Article\Update;
use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Content\Article\StoreRequest;
use App\Http\Requests\Dashboard\Content\Article\UpdateRequest;
use App\Models\Content\ContentArticle;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ArticleController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(ContentArticle::class, 'article');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Find $find)
    {
        return Inertia::render(
            component: 'Dashboard/Content/Article/Index',
            props: [
                'articles' => $find->handle($request->input('query')),
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
        return Inertia::render('Dashboard/Content/Article/Index');
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
    public function show(ContentArticle $article)
    {
        return Inertia::render(
            component: 'Dashboard/Content/Article/Index',
            props: compact('article')
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(ContentArticle $article)
    {
        return Inertia::render(
            component: 'Dashboard/Content/Article/Edit',
            props: compact('article')
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, ContentArticle $article, Update $update)
    {
        $update->handle($article, $request->only(
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
    public function destroy(ContentArticle $article, Delete $delete)
    {
        $delete->handle($article);
    }
}
