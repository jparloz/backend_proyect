<?php

namespace App\Http\Controllers;

use Symfony\Component\HttpKernel\Exception\HttpException;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdatePasswordRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{

    public function index(): JsonResponse
    {
        // Obtener todos los usuarios
        $users = User::all();

        // Retornar una respuesta o vista con los usuarios
        return response()->json($users);
    }

    public function show($username): JsonResponse
    {
        $user = User::where('name', $username)->first();

        if ($user) {
            return new JsonResponse($user);
        } else {
            return new JsonResponse(['message' => 'Usuario no encontrado'], 404);
        }
    }

    public function store(StoreUserRequest $request): JsonResponse
    {
        $response=['message' => 'Ya existe un usuario con el mismo nombre'];
        $status=201;


        $data = $request->only('name', 'email', 'password');

        $existingUser = User::where('name', $data['name'])->first();

        if ($existingUser) {
            $status=409;
            return new JsonResponse($response, $status);
        }

        $hashedPassword = Hash::make($data['password']);
        $data['password'] = $hashedPassword;

        $user = User::create($data);

        return new JsonResponse($user, $status);
    }


    public function update(Request $request, User $user): JsonResponse
    {
        $response = ['message' => 'Ya existe un usuario con el mismo nombre o email'];
        $status = 201;

        $data = $request->only('name', 'email');

        $existingUserByName = User::where('name', $data['name'])->where('id', '!=', $user->id)->first();
        $existingUserByEmail = User::where('email', $data['email'])->where('id', '!=', $user->id)->first();

        if ($existingUserByName || $existingUserByEmail) {
            $status = 409;
            return new JsonResponse($response, $status);
        } else {
            $user->update($request->only(['name', 'email']));
            return response()->json($user);
        }
    }

    public function destroy($id): JsonResponse
    {
        $user = User::findOrFail($id);

        $user->delete();

        return response()->json(['message' => 'User deleted']);
    }

    public function updatePassword(UpdatePasswordRequest $request): JsonResponse
    {

        $user = User::find($request->get("user_id"));


        if (!Hash::check($request->get('current_password'), $user->password)) {
            throw new HttpException(403, 'Contraseña inválida');
        }

        $user->password = Hash::make($request->get('new_password'));
        $user->save();

        return new JsonResponse(['message' => 'Contraseña actualizada correctamente']);
    }

    public function login(Request $request): JsonResponse
    {
        $credentials = $request->only('email', 'password');
        $response = ['message' => 'Credenciales inválidas'];
        $status = 401;

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('authToken')->plainTextToken;

            $status = 200;
            $response = [
                'token' => $token,
                'name' => $user->name,
                'email' => $user->email,
                'rol' => $user->rol,
                'id' => $user->id,
            ];
        }

        return new JsonResponse($response, $status);
    }
}
