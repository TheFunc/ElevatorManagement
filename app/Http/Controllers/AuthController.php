<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * 显示登录页面
     */
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('elevator.ledger');
        }
        return view('auth.login');
    }

    /**
     * 处理登录请求
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'password' => 'required|string',
        ], [
            'name.required' => '请输入用户名',
            'password.required' => '请输入密码',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $remember = $request->has('remember');
        
        if (Auth::attempt(['name' => $request->name, 'password' => $request->password], $remember)) {
            $request->session()->regenerate();
            return redirect()->intended(route('elevator.ledger'))->with('success', '登录成功！');
        }

        return back()->withErrors([
            'name' => '用户名或密码错误',
        ])->withInput();
    }

    /**
     * 退出登录
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', '已安全退出登录');
    }
}