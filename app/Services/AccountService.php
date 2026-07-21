<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AccountService
{
    public function __construct(
        private UserRepository $userRepository,
        private ImageUploadService $imageUploadService
    ) {
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
        if (isset($data['gender'])) {
            $user->gender = $data['gender'];
        }
        $this->userRepository->save($user);
    }

    public function changePassword(User $user, string $currentPassword, string $newPassword): void
    {
        if (!Hash::check($currentPassword, $user->password)) {
            throw ValidationException::withMessages([
                'current_password' => 'Current password is incorrect.',
            ]);
        }
        $user->password = Hash::make($newPassword);
        $this->userRepository->save($user);
    }
}
