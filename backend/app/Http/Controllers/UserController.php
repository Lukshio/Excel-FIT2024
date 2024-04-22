<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Zone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class UserController extends Controller
{
    public function index (Request $request)
    {
        return self::find($request->id);
    }

    public function usersIndex()
    {
        return User::all();
    }

    public function getUser(Request $request)
    {
        return User::find($request->id);
    }

    public function createUser(Request $request)
    {
        //validation
        $user = new User();
        $user->password = Hash::make($request->password);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->save();

        return response()->json();
    }

    public function update(Request $request)
    {
        if (!User::find($request->id)) return response()->json(['error' => 'User not found'], 404);

        $user = User::find($request->id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'role' => 'required|in:admin,user',
            'password' => 'nullable',
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 403);
        }

        $data = $validator->validated();

        if(!$data['password']) unset($data['password']);
        $user->update($data);
        return User::find($request->id);
    }

    public function destroy($id)
    {
        Zone::where('user_id', $id)->update(['user_id' => NULL]);
        User::find($id)->delete();

        return response()->json();
    }
}
