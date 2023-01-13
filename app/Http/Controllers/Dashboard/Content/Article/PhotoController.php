<?php

namespace App\Http\Controllers\Dashboard\Content\Article;

use App\Actions\Content\Photo\Add;
use App\Actions\Content\Photo\Find;
use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Content\Photo\StoreRequest;
use App\Models\Content\ContentArticle;
use Inertia\Inertia;

class PhotoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ContentArticle $article, Find $find)
    {
        return Inertia::render(
            component: 'Dashboard/Content/Photo/Index',
            props: [
                'photos' => $find->all($article),
            ]
        );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(ContentArticle $article)
    {
        return Inertia::render('Dashboard/Content/Photo/Index', compact('article'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(ContentArticle $article, StoreRequest $request, Add $create)
    {
        return $create->handle(
            $article,
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
