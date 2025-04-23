<?php
  
namespace App\Http\Controllers;
  
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
  
class AuthController extends Controller
{
    public function register()
    {
        return view('auth/register');
    }
  
    public function registerSave(Request $request)
    {
        Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed'
        ])->validate();
  
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user', // Default role untuk pendaftaran
        ]);
  
        return redirect()->route('login');
    }
  
    public function login(Request $request)
    {
        // Simpan tujuan dalam session jika ada
        if ($request->has('destination')) {
            session(['login_destination' => $request->destination]);
        }
        
        return view('auth/login');
    }

   public function loginAction(Request $request)
{
    Validator::make($request->all(), [
        'email' => 'required|email',
        'password' => 'required'
    ])->validate();

    if (!Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
        throw ValidationException::withMessages([
            'email' => trans('auth.failed')
        ]);
    }

    $request->session()->regenerate();
    
    // Cek tujuan yang disimpan dalam session
    $destination = session('login_destination');
    
    // Jika ada tujuan dan user memiliki akses ke tujuan tersebut
    if ($destination == 'dashboard' && Auth::user()->role === 'admin') {
        return redirect()->route('halaman_utama');
    } elseif ($destination == 'products') {
        return redirect()->route('products');
    }
    
    // Default redirects jika tidak ada tujuan spesifik
    if (Auth::user()->role === 'admin') {
        return redirect()->route('halaman_utama');
    }
    
    return redirect()->route('products');
}

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();
  
        $request->session()->invalidate();
  
        return redirect('/login');
    }
 
    public function profile()
    {
        return view('profile');
    }
    
    // Method untuk membuat user admin (hanya untuk development)
    public function createAdmin()
    {
        User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        return "Admin berhasil dibuat";
    }
}