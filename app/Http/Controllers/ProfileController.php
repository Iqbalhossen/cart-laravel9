<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Auth;
use Illuminate\Support\Facades\Hash;
class ProfileController extends Controller
{
    public function Profile()
    {
        $data = User::where('id', Auth::id())->first();
        return view('profile' ,compact('data'));
    }
    public function ChangeProfile()
    {
        $data = User::where('id', Auth::id())->first();
        return view('ChangeProfile' ,compact('data'));
    }
    public function UpdateProfile(Request $request)
    {
        $data = User::where('id', Auth::id())->first();

        $user = array();

        $user['name'] = $request->name;
        $user['email'] = $request->email;
        $user['password'] = Hash::make($request->password);

        User::FindOrFail(Auth::id())->update($user);

        return redirect()->route('user.profile');
    }
}
