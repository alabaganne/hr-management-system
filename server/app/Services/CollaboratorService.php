<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class CollaboratorService
{
    /**
     * Create a new collaborator
     *
     * @param array $data
     * @return User
     */
    public function createCollaborator(array $data): User
    {
        // Hash password if provided
        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        // Create the user
        $collaborator = User::create($data);

        return $collaborator;
    }

    /**
     * Update an existing collaborator
     *
     * @param User $user
     * @param array $data
     * @return User
     */
    public function updateCollaborator(User $user, array $data): User
    {
        // Handle password update
        if (empty($data['password'])) {
            $data['password'] = $user->password;
        } else {
            $data['password'] = Hash::make($data['password']);
        }

        // Update the user
        $user->update($data);

        return $user->fresh();
    }

    /**
     * Set profile image for a collaborator
     *
     * @param User $user
     * @param \Illuminate\Http\UploadedFile $image
     * @return User
     */
    public function setProfileImage(User $user, $image): User
    {
        // Delete old image if exists and not default
        if ($user->image_path && $user->image_path !== 'storage/images/default-avatar.svg') {
            Storage::delete($user->image_path);
        }

        // Store new image
        $path = $image->store('public/images/profiles');

        // Update user
        $user->update(['image_path' => $path]);

        return $user->fresh();
    }

    /**
     * Delete (soft delete) a collaborator
     *
     * @param User $user
     * @return bool
     */
    public function deleteCollaborator(User $user): bool
    {
        return $user->delete();
    }

    /**
     * Permanently delete a collaborator
     *
     * @param User $user
     * @return bool
     */
    public function permanentlyDeleteCollaborator(User $user): bool
    {
        // Delete profile image if exists
        if ($user->image_path && $user->image_path !== 'storage/images/default-avatar.svg') {
            Storage::delete($user->image_path);
        }

        return $user->forceDelete();
    }

    /**
     * Restore a soft-deleted collaborator
     *
     * @param int $userId
     * @return User|null
     */
    public function restoreCollaborator(int $userId): ?User
    {
        $user = User::withTrashed()->find($userId);

        if ($user) {
            $user->restore();
            return $user->fresh();
        }

        return null;
    }

    /**
     * Get archived (soft-deleted) collaborators
     *
     * @param string $searchText
     * @param int $perPage
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getArchivedCollaborators(string $searchText = '', int $perPage = 10)
    {
        return User::onlyTrashed()
            ->doesntHave('roles')
            ->where('name', 'LIKE', '%' . $searchText . '%')
            ->paginate($perPage);
    }

    /**
     * Get collaborators count by gender
     *
     * @return array
     */
    public function getCollaboratorsByGender(): array
    {
        $maleCount = User::doesntHave('roles')->where('gender', 'male')->count();
        $femaleCount = User::doesntHave('roles')->where('gender', 'female')->count();

        return [
            'male' => $maleCount,
            'female' => $femaleCount,
        ];
    }

    /**
     * Get collaborators count by department
     *
     * @return array
     */
    public function getCollaboratorsByDepartment(): array
    {
        return User::doesntHave('roles')
            ->with('department:id,name')
            ->get()
            ->groupBy('department.name')
            ->map(fn($group) => $group->count())
            ->toArray();
    }
}
