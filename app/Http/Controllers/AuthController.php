<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required|string',
            'email' => 'required|email',
        ], [
            'email.required' => 'El campo correo es obligatorio.',
            'email.email' => 'El correo no tiene el formato correcto.',
            'password.required' => 'El campo contraseña es obligatorio.',
            
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors() ], Response::HTTP_BAD_REQUEST);
        }

        $credentials = request(['email', 'password']);
        if (!$token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Datos de acceso incorrectos. Por favor, verifica tus credenciales.'], Response::HTTP_UNAUTHORIZED);
        }

        return $this->respondWithToken($token);
    }

    public function unauthorized()
    {
        return redirect(route('login'));
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_empleado' => 'required|string|max:8|min:4|unique:users,id_empleado',
            'name' => 'required|string|max:100|min:2',
            'app' => 'required|string|max:30',
            'apm' => 'required|string|max:30',
            'password' => 'required|string|max:255|min:8',
            'email' => 'required|email|unique:users,email',
            'isActive' => 'sometimes|boolean',
            'rol' => 'sometimes|string|in:Admin,Colaborador',
            'departament_id' => 'sometimes|uuid|exists:departaments,id',
        ], [
            'name.required' => 'El campo nombre es obligatorio.',
            'name.min' => 'El nombre debe de tener al menos min caracteres.',
            'name.max' => 'El nombre no puede tener más de max caracteres.',
            'email.required' => 'El campo correo es obligatorio.',
            'email.unique' => 'El correo ya está registrado.',
            'email.email' => 'El correo no tiene el formato correcto.',
            'password.required' => 'El campo contraseña es obligatorio.',
            'password.min' => 'El campo contraseña es de mínimo 8 caracteres.',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], Response::HTTP_BAD_REQUEST);
        }

        $exists = User::where('email', htmlspecialchars($request->input('email')))->first();

        if (!$exists) {
            $new = User::create([
                'id_empleado' => htmlspecialchars($request->input('id_empleado')),
                'name' => htmlspecialchars($request->input('name')),
                'app' => htmlspecialchars($request->input('app')),
                'apm' => htmlspecialchars($request->input('apm')),
                'email' => htmlspecialchars($request->input('email')),
                'password' => Hash::make($request->input('password')),
                'isActive' => $request->input('isActive', true),
                'departament_id' => $request->input('departament_id'),
                'rol' => $request->input('rol', 'Colaborador'), 
            ]);

            if (!$new) {
                return response()->json(['error' => 'No se logró crear'], Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            return response()->json($new, Response::HTTP_CREATED);
        } else {
            return response()->json(['error' => 'Ya existe un usuario con ese email'], Response::HTTP_BAD_REQUEST);
        }
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        try {
            $token = JWTAuth::getToken(); // Obtiene el token actual
            if (!$token) {
                return response()->json(['error' => 'Token no encontrado'], Response::HTTP_BAD_REQUEST);
            }

            // Invalida el token actual
            JWTAuth::invalidate($token);

            return response()->json(['message' => 'Sesión cerrada correctamente'], Response::HTTP_OK);
        } catch (TokenInvalidException $e) {
            return response()->json(['error' => 'Token inválido'], Response::HTTP_UNAUTHORIZED);
        } catch (\Exception $e) {
            return response()->json(['error' => 'No se pudo cerrar la sesión'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        try {
            $token = JWTAuth::getToken(); // Obtiene el token actual
            if (!$token) {
                return response()->json(['error' => 'Token no encontrado'], Response::HTTP_BAD_REQUEST);
            }

            $nuevo_token = auth()->refresh();
            // Invalida el token actual
            JWTAuth::invalidate($token);
            return $this->respondWithToken($nuevo_token);
        } catch (TokenInvalidException $e) {
            return response()->json(['error' => 'Token inválido'], Response::HTTP_UNAUTHORIZED);
        } catch (\Exception $e) {
            return response()->json(['error' => 'No se pudo cerrar la sesión'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 1440
        ], Response::HTTP_OK);
    }
}
