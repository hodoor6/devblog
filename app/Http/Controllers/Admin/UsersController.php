<?php

namespace App\Http\Controllers\Admin;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Validation\Rule;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::All();
        $storage = Storage::disk('public');
        $filePath = 'images/index/header.jpg';
        if ($storage->exists($filePath)) {
            $image = $storage->url($filePath);
        } else {
            $image = null;
        }
        return view('admin.users.index', ['users' => $users, 'image' => $image]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:users,name|max:10',
            'email' => 'required|email|unique:users,email',
            'password' => 'required',
            // 'avatar'=>'required|mimes:jpg,jpeg,bmp,png|dimensions:min_width=50','min_height=50'
            'avatar' => 'nullable|image'
        ]);
        $user = User::add($request->except('password'));
        $user->generatePassword($request->get('password'));
        $user->uploadAvatar($request->file('avatar'));
        return redirect()->route('users.index');

    }


    public function edit($id)
    {

        $user = User::find($id);

        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = User::find($id);

        $this->validate($request, [
            'name' => 'required|unique:users,name,' . $id . '|max:10',
//            'email'=>'required|email|unique:users,email,'.$id,
            'email' => [
                'required', 'email',
                Rule::unique('users')->ignore($user->id)

            ],

            'password' => 'nullable',
            // 'avatar'=>'required|mimes:jpg,jpeg,bmp,png|dimensions:min_width=50','min_height=50'
            'avatar' => 'nullable|image'
        ]);

        $user->edit($request->all());
        $user->generatePassword($request->get('password'));
        $user->uploadAvatar($request->file('avatar'));

        return redirect()->route('users.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user = User::find($id)->remove();
        return redirect()->route('users.index');
    }
}
