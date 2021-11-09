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
        $payload = [];
        $skillExperiences = SkillExperience::all();

        foreach(auth()->user()->skills->groupBy('name') as $skills) {
            $data = [
                'name' => $skills->first()->name,
                'current_exp' => $skills->sum('pivot.experience'),
            ];

            $data['current_level'] = $skillExperiences->where('min_exp', '<=', $data['current_exp'])->first()->level;
            $data['level_name'] = $skillExperiences->where('level', $data['current_level'])->first()->name;
            $data['min_exp'] = $data['current_level'] == 1 ? 0 : $skillExperiences->where('level', $data['current_level'])->first()->min_exp;
            $data['max_exp'] = $data['current_level'] == 3 ? $data['current_exp'] : $skillExperiences->where('level', $data['current_level'] + 1)->first()->min_exp;

            $payload[] = $data;
        }

        return view('pages.user.profile', compact('payload'));
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
