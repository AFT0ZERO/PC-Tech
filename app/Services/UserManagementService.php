<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserManagementService
{
    public function __construct(
        private UserRepository $userRepository,
        private ImageUploadService $imageUploadService
    ) {
    }

    public function list(?string $search = null, ?string $role = null, string $sort = 'created_desc')
    {
        return $this->userRepository->paginateWithSearchAndSort($search, $role, $sort);
    }

    public function create(array $data, ?UploadedFile $image = null): User
    {
        $data['password'] = Hash::make($data['password']);
        if ($image) {
            $data['image'] = $this->imageUploadService->uploadImage($image, 'uploads/user/');
        }
        return $this->userRepository->create($data);
    }

    public function update(User $user, array $data, ?UploadedFile $image = null): void
    {
        if ($image) {
            $user->image = $this->imageUploadService->uploadImage($image, 'uploads/user/');
        }
        if (isset($data['fname'])) {
            $user->fname = $data['fname'];
        }
        if (isset($data['lname'])) {
            $user->lname = $data['lname'];
        }
        if (isset($data['email'])) {
            $user->email = $data['email'];
        }
        if (isset($data['mobile'])) {
            $user->mobile = $data['mobile'];
        }
        if (array_key_exists('role', $data)) {
            $user->role = $data['role'];
        }
        if (isset($data['gender'])) {
            $user->gender = $data['gender'];
        }
        $this->userRepository->save($user);
    }

    public function delete(User $user): void
    {
        $this->userRepository->delete($user);
    }

    public function listTrashed()
    {
        return $this->userRepository->onlyTrashedPaginate();
    }

    public function restore(int $id): void
    {
        $user = $this->userRepository->findWithTrashed($id);
        $this->userRepository->restore($user);
    }

    public function changePassword(User $user, string $currentPassword, string $newPassword): void
    {
        if (!Hash::check($currentPassword, $user->password)) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'current_password' => 'Current password is incorrect.',
            ]);
        }
        $user->password = Hash::make($newPassword);
        $this->userRepository->save($user);
    }
}
