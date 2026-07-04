<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function login(): View
    {
        return view('auth.login');
    }

    public function register(): View
    {
        return view('auth.register');
    }

    public function loginProcess(AuthRequest $request)
    {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();

            return $this->redirectToDashboard($user);
        }

        return back()->withInput()->with('notification', [
            'status' => false,
            'message' => 'Email atau password salah.',
        ]);
    }

    public function registerProcess(AuthRequest $request)
    {
        $existingUser = User::where('email', $request->email)->first();

        if ($existingUser) {
            return back()->withInput()->with('notification', [
                'status' => false,
                'message' => 'Email sudah terdaftar.',
            ]);
        }

        $user = User::create([
            'fullname' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'role' => $request->role,
        ]);

        return redirect()
            ->route('login')
            ->with('notification', [
                'status' => true,
                'message' => 'Berhasil melakukan register. Silahkan login.',
            ]);
    }

    public function studentDashboard()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('notification', [
                'status' => false,
                'message' => 'Silakan login terlebih dahulu.',
            ]);
        }

        return view('student.dashboard', ['user' => Auth::user()]);
    }

    public function lecturerDashboard()
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('notification', [
                'status' => false,
                'message' => 'Silakan login terlebih dahulu.',
            ]);
        }

        return view('lecturer.dashboard', ['user' => Auth::user()]);
    }

    private function redirectToDashboard(User $user)
    {
        if ($user->role === 'lecturer') {
            return redirect()->route('dashboard.lecturer')->with('notification', [
                'status' => true,
                'message' => 'Berhasil login. Selamat datang.',
            ]);
        }

        return redirect()->route('dashboard.student')->with('notification', [
            'status' => true,
            'message' => 'Berhasil login. Selamat Datang.',
        ]);
    }

    public function logout()
    {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();

        return redirect('/')->with('notification', [
            'status' => true,
            'message' => 'Anda telah logout.',
        ]);
    }
}
