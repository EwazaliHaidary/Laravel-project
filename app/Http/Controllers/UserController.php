<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function uploadAvatar(Request $request)
    {
        // Validate the uploaded avatar
        $request->validate([
            'avatar' => [
                'required',
                'file',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048',
                'dimensions:max_width=4000,max_height=4000',
            ],
        ]);

        $user = $request->user();

        // Store the new avatar
        $newAvatarPath = $request->file('avatar')
            ->store('avatars', 'public');

        // Delete the old avatar if it exists
        if ($user->avatar_path) {
            Storage::disk('public')->delete($user->avatar_path);
        }

        // Update the user's avatar path
        $user->avatar_path = $newAvatarPath;
        $user->save();

        return response()->json([
            'message' => 'Avatar uploaded successfully.',
            'avatar_url' => Storage::disk('public')->url($user->avatar_path),
        ]);
    }
}
