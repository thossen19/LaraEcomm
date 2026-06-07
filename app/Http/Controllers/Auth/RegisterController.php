<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'role' => 'required|in:customer,seller',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'is_active' => true,
        ]);

        // Assign selected role
        $role = $request->role ?? 'customer';
        $user->assignRole($role);

        // Log the user in automatically after registration
        Auth::login($user);

        // Redirect to appropriate dashboard after registration
        if ($role === 'seller') {
            // Create a shop for the seller
            $user->shop()->create([
                'name' => $user->name . ' Shop',
                'description' => 'Welcome to ' . $user->name . '\'s shop!',
                'is_active' => true,
            ]);
            
            return redirect()->route('seller.dashboard')->with('success', 'Registration successful! Welcome to ' . config('app.name') . '!');
        } else {
            return redirect()->route('customer.dashboard')->with('success', 'Registration successful! Welcome to ' . config('app.name') . '!');
        }
    }
}
