<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Services\CollaboratorService;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    protected CollaboratorService $collaboratorService;

    public function __construct(CollaboratorService $collaboratorService)
    {
        $this->collaboratorService = $collaboratorService;
    }

    public function update(Request $request) {
        $user = auth()->user();

        $validated = $request->validate([
            'id' => 'required|integer',
            'name' => 'required',
            'username' => 'alphanum|unique:users,username,' . $user->id,
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:8|confirmed',
        ]);
        $request->validate(['password_confirmation' => 'nullable|min:8']);

        if($user->id !== $validated['id']) {
            return response()->json([], 401);
        }

        $updatedUser = $this->collaboratorService->updateCollaborator($user, $validated);
        return response()->json(new UserResource($updatedUser), 200);
    }

    public function setProfileImage(Request $request, User $user) {
        if ($request->hasFile('profile_image')) {
            $updatedUser = $this->collaboratorService->setProfileImage($user, $request->file('profile_image'));
            return response()->json($updatedUser->image_path, 200);
        }

        return response()->json($user->image_path, 200);
    }
}