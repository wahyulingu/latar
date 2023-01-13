<?php

namespace App\Http\Controllers\Dashboard\Profile;

use App\Actions\Profile\Owner\Check;
use App\Actions\Profile\Owner\Create;
use App\Actions\Profile\Owner\Delete;
use App\Actions\Profile\Owner\Find;
use App\Actions\Profile\Owner\Update;
use App\Exceptions\Dashboard\Profile\ProfileExistsException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Dashboard\Profile\Owner\StoreRequest;
use App\Http\Requests\Dashboard\Profile\Owner\UpdateRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class OwnerController extends Controller
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
            component: 'Dashboard/Profile/Owner/Index',
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
        return Inertia::render('Dashboard/Profile/Owner/Index');
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
            abort(422, trans('dashboard.profile.owner.store.found', ['username' => $user->username]));
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
                component: 'Dashboard/Profile/Owner/Index',
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
            component: 'Dashboard/Profile/Owner/Edit',
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

            return $update->handle($user->ownerProfile, $request->all());
        }

        abort(422, trans('dashboard.profile.owner.update.notfound', ['username' => $user->username]));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $owner, Delete $delete)
    {
        $delete->handle($owner);
    }
}
