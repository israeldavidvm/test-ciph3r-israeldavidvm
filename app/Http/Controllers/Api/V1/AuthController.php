<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Validator;
use OpenApi\Attributes as OA;

class AuthController extends Controller
{
    #[OA\Post(
        path: '/api/v1/auth/login',
        summary: 'Authenticates a user and generates a Bearer Token.',
        description: 'Allows a user to log in with their email and password to receive an access token for API authentication.',
        security: [['sanctum' => []]],
        tags: ['Auth'],
        requestBody: new OA\RequestBody(
            required: true,
            description: 'User Credentials',
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'email', type: 'string', example: 'israeldavidvm@gmail.com'),
                    new OA\Property(property: 'password', type: 'string', pattern: '^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[.$%@!&*+]).*$', example: 'Password1234.'),
                ]
            ),
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Login successful',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Login successful'),
                        new OA\Property(property: 'userData', ref: '#/components/schemas/User'),
                        new OA\Property(property: 'access_token', type: 'string', example: '2|Cfz3yDjKqUh55AUI6I9nQQv6MEHsEqQvJToMDnJ7e7c8478a'),
                        new OA\Property(property: 'type_token', type: 'string', example: 'Bearer'),
                    ]
                )
            ),
            new OA\Response(
                response: 422,
                description: "Unprocessable Entity",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "message", type: "string"),
                        new OA\Property(property: "errors", type: "object"),
                    ]
                )
            )
        ]
    )]
    public function login(Request $request): JsonResponse
    {
        $credentials = $request->only('email', 'password');

        $validator = Validator::make(
            $credentials,
            [
                'email' => [
                    'required',
                    'email:rfc,dns',
                    'exists:users,email'
                ],
                'password' => [
                    'required'
                ]
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'message' => 'No se pudo iniciar sesión, revise los datos ingresados',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::firstWhere('email', $credentials['email']);

        if (Hash::check($credentials['password'], $user->password) === false) {
            return response()->json(['message' => 'No se pudo iniciar sesión, tus datos son incorrectos'], 422);
        }

        $token = $user->createToken('API Token')->plainTextToken;

        return response()->json([
            'message' => "Inicio de sesión exitoso",
            'userData' => $user,
            'access_token' => $token,
            'type_token' => "Bearer",
        ], 200);
    }

    #[OA\Post(
        path: '/api/v1/auth/me',
        tags: ['Auth'],
        summary: 'Retrieve authenticated user data.',
        description: 'Returns the information of the currently authenticated user.',
        security: [['sanctum' => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Successful response (OK)',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Successfully retrieved user data.'),
                        new OA\Property(property: 'userData', ref: '#/components/schemas/User')
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: 'Unauthorized',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Unauthorized.')
                    ]
                )
            )
        ]
    )]
    public function me(): JsonResponse
    {
        return response()->json([
            "message" => "Se logró recuperar los datos del usuario de forma satisfactoria",
            "userData" => Auth::user()
        ], 200);
    }

    #[OA\Post(
        path: '/api/v1/auth/logout',
        tags: ['Auth'],
        summary: 'Log out.',
        description: 'Logs out the current user session by revoking the access token.',
        security: [['sanctum' => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Successful response (OK)',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Session closed successfully.')
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: 'Unauthorized',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Unauthorized.')
                    ]
                )
            ),
            new OA\Response(
                response: 500,
                description: 'Internal Server Error',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Error logging out: [error message]')
                    ]
                )
            )
        ]
    )]
    public function logout(Request $request): JsonResponse
    {
        try {

            $currentAccessToken = Auth::user()->currentAccessToken();

            $currentAccessToken->delete();

            return response()->json(
                [
                    'message' => "Sesión cerrada con éxito",
                    // 'deletedTokenID'=> $currentAccessToken->id,
                ],
                200
            );
        } catch (Exception $e) {
            return response()->json(['message' => 'Error al cerrar sesión: ' . $e->getMessage()], 500);
        }
    }

    #[OA\Post(
        path: '/api/v1/auth/register',
        tags: ['Auth'],
        summary: 'Create a new user.',
        description: 'Registers a new user in the database with provided credentials.',
        // If this route is public and does not require prior authentication, remove the security line below.
        // security: [['sanctum' => []]],
        requestBody: new OA\RequestBody(
            required: true,
            description: 'New user data',
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'name', type: 'string', example: 'John Doe'),
                    new OA\Property(property: 'email', type: 'string', format: 'email', example: 'user@example.com'),
                    new OA\Property(property: 'password', type: 'string', format: 'password', example: 'password123'),
                ],
                required: ['name', 'email', 'password']
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: 'User created successfully (Created)',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'User created successfully.'),
                        new OA\Property(property: 'data', ref: '#/components/schemas/User')
                    ]
                )
            ),
            new OA\Response(
                response: 422,
                description: 'Unprocessable Entity',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Could not create user, please check the entered data.'),
                        new OA\Property(property: 'errors', type: 'object', example: ['email' => ['The email has already been registered.']])
                    ]
                )
            )
        ]
    )]
    public function register(Request $request): JsonResponse
    {
        $request['action'] = 'create';

        
            $errors=User::validateAttributes($request->all());
        
            if($errors!=null){
                return response()->json([
                    'message' => 'No se pudo crear el usuario, revise los datos ingresados',
                    'errors' => $errors
                ], 422);
            }
    

        $user = new User();
        $user->initAttributes($request->all());
        $user->save();

        return response()->json([
            'message' => 'User created successfully',
            'data' => $user
        ], 201);
    }
}