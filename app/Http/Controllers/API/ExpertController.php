<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\User;
use App\ExpertInfo;
use App\Profile;

class ExpertController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $experts = User::where("type", "Expert")->latest()->get();

        foreach ($experts as $expert) {
            $followers = $expert->followers()->get();
            $expert['followers'] = $followers;
            $expert->expert_info;
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
        // return $request;

        $this->validate($request, [
            'name' => 'required|string|max:191',
            'username' => 'required|string|max:191|unique:users',
            'email' => 'required|string|email|max:191|unique:users',
            'password' => 'required|string|min:8',

            //Experties
            'categories' => 'required',
            'jobs' => 'required',

            //Career
            'work' => 'required',
            'company_name' => 'required',
            'position' => 'required',

            //Achievements
            'school' => 'required',
            'degree' => 'required',
        ]);

        $user =  User::create([
            'name' => $request['name'],
            'username' => $request['username'],
            'email' => $request['email'],
            'type' => 'Expert',
            'password' => Hash::make($request['password'])
        ]);

        //If Photo is Available
        if ($request["photo"]) {
            $name = time() . '.' . explode('/', explode(':', substr($request["photo"], 0, strpos($request["photo"], ';')))[1])[1];

            \Image::make($request["photo"])->save(public_path('storage/profile_photo/') . $name);
        } else {
            $name = "profile.png";
        }

        if ($user) {
            $profile = Profile::create([
                'user_id' => $user->id,
                'photo' => $name,
            ]);
            if ($profile) {
                if ($request->attachments) {
                    $dir = 'storage/expert_attachments/';
                    if (!is_dir($dir)) {
                        mkdir($dir);
                    }

                    Storage::makeDirectory('public/expert_attachments/' . $user->id);
                    $files = $request->attachments;
                    $attachments = [];
                    foreach ($files as $file) {
                        $name = uniqid() . time() . '.' . explode('/', explode(':', substr($file['url'], 0, strpos($file['url'], ';')))[1])[1];
                        //Save without Resizing
                        \Image::make($file['url'])->save(public_path($dir . $user->id . '/') . $name);

                        // Resized image
                        /*  $img = \Image::make($file['url']);
                        $img->resize(500, 500, function ($constraint) {
                            $constraint->aspectRatio();
                        }); */

                        // Canvas image
                        /*  $canvas = \Image::canvas(500, 500);
                        $canvas->insert($img, 'center');
                        $canvas->save(public_path($dir) . $name); */
                        array_push($attachments, $name);
                    }
                }

                ExpertInfo::create([
                    //Experties
                    'user_id' => $user->id,
                    'website' => $request['website'],
                    'categories' => $request['categories'],
                    'jobs' => $request['jobs'],

                    //Career
                    'work' => $request['work'],
                    'company_name' => $request['company_name'],
                    'position' => $request['position'],
                    'work_description ' => $request['work_description'],

                    //Achievements
                    'school' => $request['school'],
                    'degree' => $request['degree'],
                    'trainings' => $request['trainings'],
                    'attachments' => $attachments,
                ]);
            }
        }

        return $user;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $expert = User::findOrFail($id);
        $expert->expert_info;

        return $expert;
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
        //
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
