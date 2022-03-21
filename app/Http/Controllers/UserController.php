<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     * 
     * @return \Illuminate\Http\Response 
     */
    public function index()
    {
        $users = User::latest()->paginate(10);
        return response()->json([
            'success' => true,
            'data' => $users,
            'message' => 'User not found'
        ], 404);
    }

    /**
     * Display the specified resource.
     * 
     * @return \Illuminate\Http\Response 
     */
    public function getDetails(Request $request)
    {
        $found = User::where('id', $request->id)->get();

        if ($found->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }

        return response()->json(
            [
                'success' => true,
                'message' => 'User fetched successfully!',
                'user' => $found
            ],
            200
        );
    }

    /**
     * Update the specified resource in storage.
     * 
     * @return \Illuminate\Http\Response 
     */
    public function update(Request $request)
    {
        $found = User::find($request->id);
        if ($found->isEmpty()) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email'
        ]);
        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $found->update($request->all());

        return response()->json(
            [
                'success' => true,
                'message' => 'User updated successfully!',
                'user' => $found
            ],
            201
        );
    }

    /**
     * Remove the specified resource from storage.
     * 
     * @return \Illuminate\Http\Response 
     */
    public function destroy(Request $request)
    {
        $found = User::find($request->id);
        if (!$found) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $found->delete();

        return response()->json(
            [
                'success' => true,
                'message' => 'User deleted successfully'
            ],
            404
        );
    }
}
