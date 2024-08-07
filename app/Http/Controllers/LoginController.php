<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Exception;

class LoginController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }

    public function customLogin(Request $request)
    {
        try {
            $validator = $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);

            $credentials = $request->only('email', 'password');
            if (Auth::attempt($credentials)) {
                return redirect()->intended('dashboard')
                            ->withSuccess('Signed in');
            }
            $validator['emailPassword'] = 'Email address or password is incorrect.';
            return redirect("login")->withErrors($validator);
        } catch (Exception $e) {
            return redirect("login")->withErrors(['error' => 'An error occurred: ' . $e->getMessage()]);
        }
    }

    public function registration()
    {
        return view('auth.registration');
    }

    public function customRegistration(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required',
                'email' => 'required|email|unique:users',
                'password' => 'required|min:6',
            ]);

            $data = $request->all();
            $this->create($data);

            return redirect("dashboard")->withSuccess('You have signed-in');
        } catch (Exception $e) {
            return redirect("registration")->withErrors(['error' => 'An error occurred: ' . $e->getMessage()]);
        }
    }

    public function create(array $data)
    {
        try {
            return User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password'])
            ]);
        } catch (Exception $e) {
            throw new Exception('Failed to create user: ' . $e->getMessage());
        }
    }

    public function dashboard()
    {
        try {
            if (Auth::check()) {
                $posts = Post::where('user_id', Auth::id())->get();
                return view('dashboard', compact('posts'));
            }

            return redirect("login")->withSuccess('You are not allowed to access');
        } catch (Exception $e) {
            return redirect("login")->withErrors(['error' => 'An error occurred: ' . $e->getMessage()]);
        }
    }

    public function signOut()
    {
        try {
            Session::flush();
            Auth::logout();

            return redirect('login');
        } catch (Exception $e) {
            return redirect('login')->withErrors(['error' => 'An error occurred: ' . $e->getMessage()]);
        }
    }
}
