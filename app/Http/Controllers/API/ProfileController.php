<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Profile;

class ProfileController extends Controller
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
        $user = auth('api')->user(); 
        $followers = $user->followers()->get()->count();
        $followings = $user->followings()->get()->count();
        $user['followers'] = $followers;
        $user['followings'] = $followings;
        return $user;
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    public function addTheme(Request $request)
    {
        $user = auth('api')->user()->id;
        $profile = Profile::where('user_id', $user);
        return $profile->update($request->all());
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function profile(Request $request)
    {
        $user = auth('api')->user();
        $profile = Profile::where('user_id', $user->id);
        $this->validate($request, [
            'name' => 'required|string|max:191',
            'username' => 'required|string|max:191|unique:users,username,'.$user->id,
            'email' => 'required|string|email|max:191|unique:users,email,'.$user->id,
            'password' => 'sometimes|string|min:8'
        ]);
        if(!empty($request->password)){
            $request->merge(['password' => Hash::make($request['password'])]);
        }
        $user->update($request->all());
        $profile->update(['bio'=> $request->profile["bio"]]);
        return ['message' => 'Info updated!'];
    }


    // UPDATING THE PROFILE PHOTO
    public function updatePhoto(Request $request)
    {
        $user = auth('api')->user()->profile;
        $currentPhoto = $user->photo;
        $name = time().'.' . explode('/', explode(':', substr($request->profile["photo"], 0, strpos($request->profile["photo"], ';')))[1])[1];
        if(!is_dir('storage/profile_photo/')) {
            mkdir('storage/profile_photo/');
        }
            \Image::make($request->profile["photo"])->save(public_path('storage/profile_photo/').$name);
            $request->merge(['photo' => $name]);

             //Deleting current profile photo
            if($currentPhoto != 'profile.png'){
                $userPhoto = public_path('storage/profile_photo/').$currentPhoto;
                if(file_exists($userPhoto)){
                    @unlink($userPhoto);
                }
            }
        $user->update($request->all());

        return $name;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
