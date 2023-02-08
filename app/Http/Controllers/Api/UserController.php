<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\UserUpdateRequest;
use App\Http\Requests\UserStoreRequest;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserCollection;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Http\Response;

class UserController extends Controller
{
    public function index(): UserCollection
    {
        $users = User::search(request()->input('query', ''))->query(function ($q) {
            $q->select('id', 'name', 'email', 'banned_at', 'phone')
                ->when(request()->filled('status'), function ($q) {
                    switch (request('status')) {
                        case 'active':
                            $q->whereNull('banned_at');
                            break;
                        case 'banned':
                            $q->whereNotNull('banned_at');
                            break;
                        case 'online' :
                            $q->whereHas('tokens');
                            break;

                        case 'offline' :
                            $q->whereDoesntHave('tokens');
                            break;
                    }
                })
                ->orderBy(request('order.column'), request('order.sort'));
        })->paginate(10)->withQueryString();

        return new UserCollection($users);
    }

    public function store(UserStoreRequest $request): Response|Application|ResponseFactory
    {
        $payload = $request->validated();
        $payload['password'] = bcrypt($payload['password']);

        User::create($payload);

        return response('Created successful', 200);
    }

    public function edit(User $user): UserResource
    {
        return new UserResource($user);
    }

    public function update(UserUpdateRequest $request, User $user): Response|Application|ResponseFactory
    {
        $payload = $request->validated();

        if (!empty($payload['password'])) {
            $payload['password'] = bcrypt($payload['password']);
        } else {
            unset($payload['password']);
        }

        $payload['banned_at'] = $payload['banned_at'] ? now() : null;

        $user->update($payload);

        foreach($user->tokens as $token) {
            $token->revoke();
        }

        return response('Updated successful', 200);
    }
}
