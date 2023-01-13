<?php

namespace App\Http\Controllers\Dashboard\Profile;

use App\Actions\Profile\Author\Check;
use App\Actions\Profile\Author\Create;
use App\Actions\Profile\Author\Delete;
use App\Actions\Profile\Author\Find;
use App\Actions\Profile\Author\Update;
use App\Exceptions\Dashboard\Profile\ProfileExistsException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Profile\Author\StoreRequest;
use App\Http\Requests\Dashboard\Profile\Author\UpdateRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class AuthorController extends Controller
{
    public function __construct(protected Check $check)
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Find $find)
    {
        return Inertia::render(
            component: 'Dashboard/Profile/Author/Index',
            props: [
                'profiles' => $find->handle($request->input('query')),
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
        return Inertia::render('Dashboard/Profile/Author/Index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRequest $request, User $user, Create $create)
    {
        try {
            return response($create->handle(
                $user,
                $request->all()
            ), 201);
        } catch (ProfileExistsException) {
            abort(422, trans('dashboard.profile.author.store.found', ['username' => $user->username]));
        }
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, User $user)
    {
        if ($this->check->hasProfile($user)) {
            return Inertia::render(
                component: 'Dashboard/Profile/Author/Index',
                props: compact('user')
            );
        }

        abort(404);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        return Inertia::render(
            component: 'Dashboard/Profile/Author/Edit',
            props: compact('user')
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRequest $request, User $user, Update $update)
    {
        if ($this->check->hasProfile($user)) {
            Log::error(json_encode($request->all(), JSON_PRETTY_PRINT));

            return $update->handle($user->authorProfile, $request->all());
        }

        abort(422, trans('dashboard.profile.author.update.notfound', ['username' => $user->username]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $author, Delete $delete)
    {
        $delete->handle($author);
    }
}
