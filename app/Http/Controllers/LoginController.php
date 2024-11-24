<?php

namespace App\Http\Controllers;

use App\Mail\ResetPasswordMail;
use App\Models\PasswordResetToken;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class LoginController extends Controller
{
    public function index(){
        return view('auth.login');
    }

    public function forgot_password(){
        
        return view('auth.forgot-password');
    }

    public function forgot_password_act(Request $request){
        
        $customMessages = [
            'email.required' => 'Email tidak boleh kosong',
            'email.exists'   => 'Email tidak terdaftar di database',
            'email.email'    => 'Email tidak valid',
        ];

        $request->validate([
            'email'     => 'required|email|exists:users,email',
        ], $customMessages);

        $token =  \Str::random(60);

        PasswordResetToken::updateOrCreate([
            'email' => $request->email
        ],
        
[
            'email' => $request->email,
            'token' => $token,
            'created_at' => now()
        ]);

        Mail::to($request->email)->send(new ResetPasswordMail($token));

        return redirect()->route('forgot-password')->with('success', 'Silahkan cek email kamu');
    }

    public function validasi_forgot_password_act(Request $request){
        $customMessages = [
            'password.required' => 'Password tidak boleh kosong',
            'password.min'      => 'Password minimal 6 karakter',
        ];

        $request->validate([
            'password'  => 'required|min:6',
        ], $customMessages);

        $token = PasswordResetToken::where('token', $request->token)->first();

        if(!$token){
            return redirect()->route('login')->with('failed', 'Token tidak valid');
        }

        $user = User::where('email', $token->email)->first();

        if(!$user){
            return redirect()->route('login')->with('failed', 'Email tidak terdaftar di database');
        }

        $user->update([
            'password' => Hash::make($request->password)
        ]);

        $token->delete();

        return redirect()->route('login')->with('success', 'Password kamu berhasil diubah');
    }

    public function validasi_forgot_password(Request $request, $token){
        
        $getToken = PasswordResetToken::where('token', $token)->first();

        if(!$getToken){
            return redirect()->route('login')->with('failed', 'Token tidak valid');
        }

        return view('auth.validasi-token', compact('token'));
    }

    public function login_proses(Request $request){
        $request->validate([
            'email'     => 'required|email',
            'password'  => 'required',
        ]);

        $data =[
            'email'     => $request->email,
            'password'  => $request->password
        ];

        if(Auth::attempt($data)){
            return redirect()->route('admin.dashboard');
        }else{
            return redirect()->route('login')->with('failed', 'Email atau Password salah');
        }
    }
    
    public function logout(){
        Auth::logout();
        return redirect()->route('login')->with('success', 'Kamu Berhasil logout');
    }

    public function register(){
        return view('auth.register');
    }

    public function register_proses(Request $request){
        $request->validate([
            'nama'      => 'required',
            'email'     => 'required|email|unique:users,email',
            'password'  => 'required|min:6',
        ]);

        $data ['name'] = $request->nama;
        $data ['email'] = $request->email;
        $data ['password'] = Hash::make($request->password);
        
        User::create($data);

        $login =[
            'email'     => $request->email,
            'password'  => $request->password
        ];

        if(Auth::attempt($login)){
            return redirect()->route('admin.dashboard');
        }else{
            return redirect()->route('login')->with('failed', 'Email atau Password salah');
        }
    }
        
}
