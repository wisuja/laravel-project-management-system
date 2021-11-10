<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProfileRequest;
use App\Models\SkillExperience;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $skills = [];
        $skillExperiences = SkillExperience::all();

        foreach(auth()->user()->skills as $skill) {
            $data = [
                'name' => $skill->name,
                'current_exp' => $skill->pivot->experience,
                'current_level' => $skill->pivot->level,
                'level_name' => $skillExperiences->where('level', $skill->pivot->level)->first()->name,
                'min_exp' => $skill->pivot->level == 1 ? 0 : $skillExperiences->where('level', $skill->pivot->level)->first()->min_exp,
                'max_exp' => $skill->pivot->level == 3 ? $skill->pivot->experience : $skillExperiences->where('level', $skill->pivot->level + 1)->first()->min_exp,
            ];

            $skills[] = $data;
        }

        return view('pages.user.profile', compact('skills'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \App\Http\Requests\UpdateProfileRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProfileRequest $request)
    {
        $updateArray = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        if ($request->filled('old_password')) {
            $validPassword = Hash::check($request->old_password, auth()->user()->password);

            if (!$validPassword)
                abort(400); 

            $updateArray['password'] = $request->new_password;
        }

        if ($request->has('photo')) {
            $updateArray['photo'] = $request->file('photo')->store('profiles', 'public');
        }

        auth()->user()->update($updateArray);

        return redirect()->route('profile.index');
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
