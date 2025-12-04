<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class AuthController extends Controller
{


    /**
     * Handle the login request.
     */
   public function loginOrRegister(Request $request)
{
    $request->validate([
        'name' => 'required|string',
        'password' => 'required|string|min:6',
    ]);

    // Vérifie si l'utilisateur existe
    $user = User::where('name', $request->name)->first();

    if ($user) {
        // Si existe, tenter login
        if (Auth::attempt(['name' => $request->name, 'password' => $request->password])) {

            Auth::login($user);
            $request->session()->regenerate(); // Stocke l'utilisateur dans la session
            return redirect()->route('os')->with('success', 'Connecté !');
        } else {
            return back()->withErrors([
                'name' => 'Mot de passe incorrect',
            ])->withInput($request->only('name'));
        }
    } else {
        // Si n'existe pas, créer le compte et loguer
        $user = User::create([
            'name' => $request->name,
            'password' => Hash::make($request->password), // hash du password
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('os')->with('success', 'Compte créé et connecté !');
    }
}


    // LOGOUT METHOD

    /**
     * Log the user out of the application.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('auth.home');
    }
}
