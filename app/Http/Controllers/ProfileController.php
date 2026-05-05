<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Hash;

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
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
     /**
         * Summary of profile
         * @return \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
         */
        public function profile()
        {
            return view('user.profile');
    }

    /**
     * Summary of updateProfile
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
   
    public function updateProfile(Request $request)
    {
        $user = auth()->user();
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email',
            'password' => 'nullable|min:6',
            'phone_number'    => 'nullable|string|max:20',
            'avatar'    => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone_number = $request->phone_number;
        $user->gender = $request->gender;
        $user->address= $request->address;

        if ($request->password) {
            $user->password = Hash::make($request->password);
        }

        if ($request->hasFile('avatar')) {

            $destination = public_path('uploads');

            // pastikan folder ada
            if (!file_exists($destination)) {
                mkdir($destination, 0775, true);
            }

            // hapus foto lama (lebih aman pakai is_file)
            if ($user->avatar && is_file($destination . '/' . $user->avatar)) {
                unlink($destination . '/' . $user->avatar);
            }

            $file = $request->file('avatar');

            // nama file unik + aman
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

            // pindahkan file
            $file->move($destination, $filename);

            // simpan ke database
            $user->avatar = $filename;
        }

        $user->save();

        return back()->with('success', 'Profile berhasil diupdate');
    }
}

