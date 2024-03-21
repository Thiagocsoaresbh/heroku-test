<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Sanctum\Sanctum;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')->except(['store', 'login']);
    }

    public function index()
    {
        if (Auth::user()->role !== 'administrator') {
            return response()->json(['error' => 'Ação não autorizada'], 403);
        }

        $users = User::all();
        return response()->json($users);
    }

    public function store(Request $request)
    {
        $validatedData = Validator::make($request->all(), [
            'username' => 'required|unique:users|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'role' => 'required|in:customer,administrator',
        ]);

        if ($validatedData->fails()) {
            return response()->json(['errors' => $validatedData->errors()], 400);
        }

        $user = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return response()->json($user, 201);
    }

    public function show(User $user)
    {
        if (Auth::id() !== $user->id && Auth::user()->role !== 'administrator') {
            return response()->json(['error' => 'Ação não autorizada'], 403);
        }

        return response()->json($user);
    }

    public function update(Request $request, User $user)
    {
        if (Auth::id() !== $user->id && Auth::user()->role !== 'administrator') {
            return response()->json(['error' => 'Ação não autorizada'], 403);
        }

        $validatedData = Validator::make($request->all(), [
            'username' => 'unique:users,username,' . $user->id,
            'email' => 'email|unique:users,email,' . $user->id,
            'password' => 'sometimes|min:6',
            'role' => 'in:customer,administrator',
        ]);

        if ($validatedData->fails()) {
            return response()->json(['errors' => $validatedData->errors()], 400);
        }

        if (!empty($request->password)) {
            $request->merge(['password' => Hash::make($request->password)]);
        }

        $user->update($request->all());

        return response()->json($user);
    }

    public function destroy(User $user)
    {
        if (Auth::user()->role !== 'administrator') {
            return response()->json(['error' => 'Ação não autorizada'], 403);
        }

        $user->delete();

        return response()->json(null, 204);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (!Auth::attempt($credentials)) {
            return response()->json(['error' => 'As credenciais fornecidas são inválidas.'], 401);
        }

        $user = Auth::user();
        // ...
        Sanctum::actingAs($user);
        $token = Sanctum::createToken($user, ['authToken'])->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }
}
