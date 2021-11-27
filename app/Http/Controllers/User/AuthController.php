<?php

namespace App\Http\Controllers\User;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function signup(Request $request)
    {

        $request->validate([
            'name' => 'required',
            'role' => ['required', Rule::in(['hirer', 'freelancer'])],
            'username' => 'required|unique:App\Models\User,username',
            'password' => [Password::required(), Password::min(4)->numbers()/*->mixedCase()->letters()->symbols()->uncompromised()*/, 'confirmed'],
        ]);


        $user = new User();
        $user->name = $request->name;
        $user->role = $request->role;
        $user->username = $request->username;
        $user->password = Hash::make($request->password);
        $user->save();

        return $this->success('You have successfully registered, utilize your username and password to log in');
    }


    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        if (!$user = User::query()->where('username', $request->username)->first()) {
            return $this->error(Status::USERNAME_NOT_FOUND);
        }

        $pass_check = Hash::check($request->password, User::query()->where('username', $request->username)->firstOrFail()->password);

        if ($user && $pass_check) {
            return $this->success([
                'user' => $user,
                'token' => $user->createToken('token_base_name')->plainTextToken
            ]);
        } else {
            return $this->error(Status::PASSWORD_IS_WRONG);
        }

    }


    public function logout()
    {
        /** @var User $user */
        $user = auth()->user();

        $user->tokens()->delete();

        return $this->success('logged out');
    }


    public function changePass(Request $request)
    {//todo  no route
        $request->validate([
            'old_pass' => 'required',
            'new_pass' => 'required',
        ]);
        $pass_check = Hash::check($request->old_pass, User::query()->where('id', '=', auth()->id())->firstOrFail()->password);
        if ($pass_check) {
            User::query()->where('id', '=', auth()->id())->update([
                'password' => Hash::make($request->new_pass)
            ]);
            return $this->success(1,'password changed to ' . $request->new_pass);
        } else {
            return $this->error();
        }
    }
}
