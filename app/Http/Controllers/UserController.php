<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class UserController extends Controller
{

    /**
     * 检查管理员权限
     */
    private function checkAdmin()
    {
        if (Auth::user()->role != 1) {
            abort(403, '您没有权限访问此页面');
        }
    }

    /**
     * 用户列表
     */
    public function index(Request $request)
    {
        $this->checkAdmin();
        
        $query = User::query();
        
        // 搜索功能
        if ($request->has('search') && $request->search != '') {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        
        // 排序功能
        $sort = $request->get('sort', 'id');
        $order = $request->get('order', 'asc');
        
        if ($sort == 'role') {
            $query->orderBy('role', 'desc')->orderBy('id', 'asc');
        } else {
            $query->orderBy($sort, $order);
        }
        
        $users = $query->get();
        $search = $request->search;
        
        return view('user.index', compact('users', 'search', 'sort', 'order'));
    }

    /**
     * 显示创建用户页面
     */
    public function create()
    {
        $this->checkAdmin();
        return view('user.create');
    }

    /**
     * 保存新用户
     */
    public function store(Request $request)
    {
        $this->checkAdmin();
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100|unique:users',
            'password' => 'required|string|min:4|max:100',
            'role' => 'required|integer|in:0,1',
        ], [
            'name.required' => '请输入用户名',
            'name.unique' => '该用户名已存在',
            'password.required' => '请输入密码',
            'password.min' => '密码至少4个字符',
            'role.required' => '请选择用户角色',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        User::create([
            'name' => $request->name,
            'email' => $request->name . '@elevator.local',
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'email_verified_at' => now(),
        ]);

        return redirect()->route('user.index')->with('success', '用户创建成功！');
    }

    /**
     * 删除用户
     */
    public function delete($id)
    {
        $this->checkAdmin();
        $user = User::findOrFail($id);
        
        // 禁止删除超级管理员admin
        if ($user->name == 'admin') {
            return back()->with('error', '超级管理员账号不允许删除');
        }
        
        $user->delete();
        
        // 重置自增ID到当前最大ID+1
        $maxId = User::max('id');
        if ($maxId) {
            \DB::statement("ALTER TABLE users AUTO_INCREMENT = " . ($maxId + 1));
        } else {
            \DB::statement("ALTER TABLE users AUTO_INCREMENT = 1");
        }
        
        return redirect()->route('user.index')->with('success', '用户已成功删除');
    }

    /**
     * 修改用户密码
     */
    public function changePassword(Request $request, $id)
    {
        $this->checkAdmin();
        $user = User::findOrFail($id);
        
        $validator = Validator::make($request->all(), [
            'password' => 'required|string|min:4|max:100|confirmed',
        ], [
            'password.required' => '请输入新密码',
            'password.min' => '密码至少4个字符',
            'password.confirmed' => '两次密码输入不一致',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return redirect()->route('user.index')->with('success', '密码修改成功');
    }

    /**
     * 个人中心页面
     */
    public function profile()
    {
        return view('user.profile');
    }
}
