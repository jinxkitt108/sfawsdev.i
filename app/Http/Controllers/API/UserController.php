<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;
use App\User;
use App\Profile;

class UserController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api');
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return User::latest()->paginate();
    }

    public function userOnline(Request $request)
    {
        $id = auth('api')->user()->id;
        $user = User::find($id);

        $user->update($request->all());
        return dd("online user");
    }

    public function search()
    {
        $current_user = auth('api')->user();
        if ($search = \Request::get('q')) {
            $users = User::where(function ($query) use ($search) {
                $query->where('name', 'LIKE', "%$search%")
                    ->orwhere('email', 'LIKE', "%$search%")
                    ->orwhere('type', 'LIKE', "%$search%");
            })->paginate(5);
        }
        foreach($users as $user) {
            $followers = $user->followers()->get()->count();
            $followings = $user->followings()->get()->count();
            $user['followers'] = $followers;
            $user['followings'] = $followings;
            if ($current_user->isFollowing($user)) {
                $user['isFollowing'] = true;
            } else {
                $user['isFollowing'] = false;
            }
            if($current_user->id === $user->id){
                $user['current_user'] = true;
            } else {
                $user['current_user'] = false;
            }
        }
        return $users;
    }

    public function current()
    {
        return auth('api')->user();
    }

    public function findExperts()
    {
        $user = auth('api')->user();
        $experts = User::where('type', 'Expert')->where('id', '!=', $user->id)->latest()->paginate(12);
        foreach ($experts as $expert) {
            $expert->profile;
            $followers = $expert->followers()->get()->count();
            $followings = $expert->followings()->get()->count();
            $expert['followers'] = $followers;
            $expert['followings'] = $followings;
            if ($user->isFollowing($expert)) {
                $expert['isFollowing'] = true;
            } else {
                $expert['isFollowing'] = false;
            }
        }
        return $experts;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:191',
            'username' => 'required|string|max:191|unique:users',
            'email' => 'required|string|email|max:191|unique:users',
            'password' => 'required|string|min:8'
        ]);

        $user =  User::create([
            'name' => $request['name'],
            'username' => $request['username'],
            'email' => $request['email'],
            'type' => $request['type'],
            'password' => Hash::make($request['password'])
        ]);
        Profile::create([
            'user_id' => $user->id
        ]);
        return $user;
    }

    public function uniqueEmail(Request $request, $value)
    {
        return  User::where('email', $value);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $current_user = auth('api')->user();
        $user = User::findOrFail($id);
        $followers = $user->followers()->get()->count();
        $followings = $user->followings()->get()->count();
        $user['followers'] = $followers;
        $user['followings'] = $followings;
        if($current_user->id === $user->id){
            $user['current_user'] = true;
        } else {
            $user['current_user'] = false;
        }
        if ($current_user->isFollowing($user)) {
            $user['isFollowing'] = true;
        } else {
            $user['isFollowing'] = false;
        }
        return $user;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $this->validate($request, [
            'name' => 'required|string|max:191',
            'username' => 'required|string|max:191|unique:users,username,' . $user->id,
            'email' => 'required|string|email|max:191|unique:users,email,' . $user->id,
            'password' => 'sometimes|string|min:8'
        ]);
        if (!empty($request->password)) {
            $request->merge(['password' => Hash::make($request['password'])]);
        }
        $user->update($request->all());
        return $user;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $profile = Profile::where('user_id', $id);

        //Delete the user
        $delete = $user->delete();
        if($delete) {
            $profile->delete();
        }

        return ['message' => 'User Deleted'];
    }
}
