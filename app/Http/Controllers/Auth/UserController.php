<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\User as UserResource;

class UserController extends Controller
{
    /**
     * Authenticated user.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function current(Request $request)
    {
        return new UserResource($request->user());
    }

    /**
     * List of users.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        return UserResource::collection(User::all());
    }

    /**
     * Store a newly created user in storage.
     * 
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|max:255',
            'email' => 'required|email:filter|max:255|unique:users',
            'hobbies' => 'required',
            'phone' => 'required|digits:10',
            'password' => 'required|min:6',
            'country_id' => 'required'
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                "message"=> "The given data was invalid.",
                "errors" => $validator->getMessageBag()->getMessages()
            ], 422);
        }
        $splitName = explode(' ', $request->name, 2);
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'firstname' => $splitName[0],
            'lastname' => $splitName[1] ?? '',
            'hobbies' => $request->hobbies,
            'phone' => $request->phone,
            'country_id' => $request->country_id,
            'password' => bcrypt($request->password),
        ]);

        return new UserResource($user);
    }

    /**
     * Update the specified user in storage
     * 
     * @param  \Illuminate\Http\Request $request
     * @param \App\Models\User $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, User $user)
    {
        $rules = [
            'name' => 'required|max:255',
            'email' => 'required|email:filter|max:255|unique:users',
            'hobbies' => 'required',
            'phone' => 'required|digits:10',
            'password' => 'required|min:6',
            'country_id' => 'required'
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                "message"=> "The given data was invalid.",
                "errors" => $validator->getMessageBag()->getMessages()
            ], 422);
        }
        $splitName = explode(' ', $request->name, 2);
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'firstname' => $splitName[0],
            'lastname' => $splitName[1] ?? '',
            'hobbies' => $request->hobbies,
            'phone' => $request->phone,
            'country_id' => $request->country_id,
            'password' => bcrypt($request->password),
        ]);

        return new UserResource($user);
    }

    /**
     * Remove the specified user from storage.
     * 
     * @param \App\Models\User $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function destory(User $user)
    {
        $user->delete();

        return response()->json([
            'status' => 'success'
        ]);
    }

    /**
     * List of countries
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCountries()
    {
        $countryArray = [
            [
                'id' => 1,
                'name' => 'United Kingdom'
            ],
            [
                'id' => 2,
                'name' => 'China',
            ],
            [
                'id' => 3,
                'name' => 'United States'
            ]
        ];

        return response()->json($countryArray);
    }

    /**
     * 
     */
    // public function getUsers()
    // {
    //     $users = User::all();
    //     $result = [];
    //     foreach ($users as $user) {
    //         array_push($result, [
    //             'id' => $user->id,
    //             'name' => $user->name
    //         ]);
    //     }

    //     return response()->json($result);

    // }
}
