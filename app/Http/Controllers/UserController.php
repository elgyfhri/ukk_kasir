<?php

namespace App\Http\Controllers;

use App\Models\detail_sales;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Exports\UsersExport;
use Maatwebsite\Excel\Facades\Excel;

class UserController extends Controller
{
    
    public function loginpage()
    {
        return view('welcome');
    }

    
    public function export()
    {
        return Excel::download(new UsersExport, 'data-user.xlsx');
    }

        public function login(Request $request){
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ],[
            'email.required' => 'silahkan isi email',
            'password.required' => 'silahkan isi password',
        ]);

        $cekLogin = [
            'email' => $request->email,
            'password'=>$request->password,
        ];

        if(Auth::attempt($cekLogin)){
            $role = Auth::user();
            if ($role->role == 'admin') {
                return redirect()->route('dashboard');
            }
            if ($role->role == 'employee') {
                return redirect()->route('dashboard');
            }
        }else {
            return redirect()->back()->withErrors(['login_failed' => 'Proses login gagal, silakan coba lagi dengan data yang benar!'])->withInput();
        };
    }

    public function index()
    {
        $users = User::all();
        return view('module.user.index', compact('users'));
    }

    public function create()
    {
        return view('module.user.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'role' => 'required',
            'password' => 'required'
        ]);

        if (User::where('email', $request->email)->exists()) {
            return redirect()->back()->withErrors(['email' => 'Email sudah digunakan.'])->withInput();
        }

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => $request->role,
        ]);
    
        return redirect()->route('user.list')->with('success', 'Berhasil Menambah User');
    }
    
    public function edit($id)
    {
        try {
            $item = User::findOrFail($id);
            return view('module.user.edit', compact('item'));
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return redirect()->route('user.list')->with('error', 'User tidak ditemukan!');
        }
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required',
            'role' => 'required',
        ]);
    
        $user = User::findOrFail($id);
    
        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ];
    
        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        }
    
        $user->update($data);

        // dd($user);
        return redirect()->route('user.list')->with('success', 'User berhasil diperbarui!');
    }


    public function destroy(User $User, $id)
    {
        User::where('id', $id)->delete();
        return redirect()->route('user.list')->with('success', 'Berhasil Hapus User');
    }
    
    public function logout()
    {
        Auth::logout();
        return redirect()->route('login')->with('logout', 'Anda telah berhasil logout!');
    }
}

