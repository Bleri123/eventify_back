<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UsersController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $users = User::select('id', 'first_name', 'last_name', 'email', 'phone_number', 'city', 'address')
            ->where('is_active', true)
            ->orderBy('id')
            ->get();

        return response()->json($users);
    }

    public function update(Request $request, $id): JsonResponse
    {
        $user = User::findOrFail($id);

        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'first_name' => 'required|string|max:150',
            'last_name' => 'required|string|max:150',
            'email' => 'required|string|email|max:150|unique:users,email,' . $id,
            'phone_number' => 'required|string|max:50',
            'city' => 'required|string|max:100',
            'address' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'error' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user->update([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'city' => $request->city,
            'address' => $request->address,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'User updated successfully',
            'user' => $user
        ]);
    }

    public function destroy(Request $request, $id): JsonResponse
    {
        try {
            $user = User::findOrFail($id);
            $currentUser = auth()->user();

            // Prevent users from deactivating themselves
            if ($currentUser && $currentUser->id == $user->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'You cannot deactivate your own account'
                ], 403);
            }

            $user->update([
                'is_active' => false
            ]);

            return response()->json([
                'success' => true,
                'message' => 'User deactivated successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to deactivate user: ' . $e->getMessage()
            ], 500);
        }
    }
}
