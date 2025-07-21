<?php

namespace App\Http\Controllers\users;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->has('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        $users = $query->get()->map(function ($user) {
            if ($user->image) {
                $user->image = asset('storage/' . $user->image);
            }
            return $user;
        });

        return UserResource::collection($users);
    }

    public function store(StoreUserRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('users', $filename, 'public');
            $data['image'] = 'users/' . $filename;
        }

        $data['password'] = Hash::make($data['password']);

        $user = User::create($data);

        if ($user->image) {
            $user->image = asset('storage/' . $user->image);
        }

        $user = new UserResource($user);
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            "user" => $user,
            "token" => $token
        ]);
    }

    public function show(User $user)
    {
        if ($user->image) {
            $user->image = asset('storage/' . $user->image);
        }

        return new UserResource($user);
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('users', $filename, 'public');
            $data['image'] = 'users/' . $filename;
        }

        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        $user->update($data);

        if ($user->image) {
            $user->image = asset('storage/' . $user->image);
        }

        return new UserResource($user);
    }

    public function destroy(User $user)
    {
        if (!$user) {
            return response()->json(['massage' => "User Not Found"]);
        }

        $user->delete();

        return response()->json(['massage' => "user deleted sucssfully"], 200);
    }
}
