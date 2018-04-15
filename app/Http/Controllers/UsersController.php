<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\User;

class UsersController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function index()
    {
        $users = User::inRandomOrder()->limit(10)->get();
        if (count($users) > 0) {
            return response()->json(['status' => true, 'message' => 'success', 'data' => $users]);
        } else {
            return response()->json(['status' => false, 'message' => 'no user found']); 
        } 
    }

    public function register(Request $request)
    {
        $this->validate($request, [
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'phone' => 'required', 
            'gender' => 'required',
            'role' => 'required'
        ]);

        $request['api_token'] = str_random(60);
        $request['password']  = app('hash')->make($request['password']);
        $user = User::create($request->all());

        if ($user) {

            return response()->json(['status' => true, 'message' => 'success', 'data' => $user]);

        } else {

            return response()->json(['status' => false, 'message' => 'failure']);
        }
    }

    public function delete($id)
    {
        if ($id != null) {

            $user = User::findOrFail($id);
            if ($user->delete()) {
                return response()->json(['status' => true, 'message' => 'deleted']);
            } else {
                return response()->json(['status' => false, 'message' => 'not deleted']);
            }
            
        } else {
            return response()->json(['status' => false, 'message' => 'id cannot be null']);
        }
        
    }

    public function role($role)
    {   
        if ($role != null) {

            $user = User::where('role', $role)->get();

            if (count($user) > 0) {
                return response()->json(['status' => true, 'message' => 'success', 'data' => $user]);
            } else {
                return response()->json(['status' => false, 'message' => 'no data found']);
            }   
        } else {
            return response()->json(['status' => false, 'message' => 'id cannot be null']);
        }
        
    }

    public function authenticate(Request $request)
    {

        $this->validate($request, [

          'email' => 'required|email',
          'password' => 'required|min:6'
      ]);

        $user = user::where('email', $request->email)->first();

        if (!$user) {

            return response()->json(['status' => false, 'message' => 'email incorrect']);
        }

        else if(Hash::check($request->password, $user->password)){

            $apikey = base64_encode(str_random(60));
            User::where('email', $request->email)->update(['api_token' => $apikey]);

            return response()->json(['status' => true, 'message' => 'success', 'data' => ['role' => $user['role'], 'user_id' => $user['id'], 'api_token' => $apikey]]);

        }else{

            return response()->json(['status' => false, 'message' => 'password incorrect']);

        }
    } 

    public function singleUser($user_id)
    {
        if ($user_id != null) {

            $user = User::where('id', $user_id)->get();
            if (count($user) > 0) {
                return response()->json(['status' => true, 'message' => 'success', 'data' => $user]);
            } else {
                return response()->json(['status' => false, 'message' => 'user not found']);
            }
            
        } else {
            return response()->json(['status' => false, 'message' => 'id cannot be null']);
        }
        
    }

    public function forgotpassword(Request $request){

        $this->validate($request, [
          'email' => 'required|email'
      ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {

          return response()->json(['status' => false, 'message' => 'email not found']);
      }
      else{

          $request['password']  = app('hash')->make($request['password']);
          $user = User::where('email', $request->email)->update(['password' => $request['password']]);

          if ($user) {
            return response()->json(['status' => true, 'message' => 'success']);
        } else {
            return response()->json(['status' => false, 'message' => 'failure']);
        }


    }
    
}

}
