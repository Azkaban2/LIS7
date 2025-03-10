<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'licensed_number' => ['required', 'string'],
        ]);

        $request->user()->update($request->only('name', 'email', 'licensed_number'));

        return Redirect::route('profile.edit')->with('status', 'Profile information updated successfully!');
    }

    /**
     * Change the user's password.
     */
    public function changePassword(Request $request): RedirectResponse
    {
        $request->validate([
            'current_password' => ['required', 'current_password:web'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $request->user()->update([
            'password' => Hash::make($request->password),
        ]);

        return Redirect::route('profile.edit')->with('status', 'Password successfully updated!');
    }

  /**
     * Upload and update the user's profile picture.
     */
    public function updateProfilePicture(Request $request): RedirectResponse
    {
        $request->validate([
            'profile_picture' => ['required', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);
    
        $user = $request->user();
    
        // Delete old picture if it exists
        if ($user->profile_picture && Storage::disk('public')->exists($user->profile_picture)) {
            Storage::disk('public')->delete($user->profile_picture);
        }
    
        // Store new profile picture
        $path = $request->file('profile_picture')->store('profile_pictures', 'public');
    
        // Update user profile picture
        $user->update(['profile_picture' => $path]);
    
        // Refresh user object
        $user->refresh();
    
        return Redirect::route('profile.edit')->with('status', 'Profile picture updated successfully!');
    }
    

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/')->with('status', 'Account successfully deleted.');
    }
}
