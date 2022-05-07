<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try{
            return response()->json([
                'success'   => true,
                'message'   => 'users retrieved successfully',
                'data'      =>User::with('transactions')->get()
            ]);
        }
        catch (\Exception $exception){
            return response()->json([
                'success'   => false,
                'message'   =>$exception->getMessage()
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try{
            $user=User::create([
                'name'  =>$request->input('name'),
                'email'  =>$request->input('email'),
                'password'  =>Hash::make($request->input('password')),
            ]);
            return response()->json([
                'success'   => true,
                'message'   => 'user added successfully',
                'data'      =>$user
            ]);
        }catch (\Exception $e){
            return response()->json([
                'success'   => false,
                'message'   =>$e->getMessage()
            ]);
        }
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
