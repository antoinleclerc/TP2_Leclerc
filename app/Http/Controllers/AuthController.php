<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use OpenApi\Attributes as OA;
class AuthController extends Controller
{
// L'intelisence à aider à faire les OA
    #[OA\Post(
        path: '/api/signup',
        summary: 'Enregistrer un nouvel utilisateur',
        description: 'Créer un nouvel utilisateur avec les informations fournies',
        tags: ['Authentification'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'login', type: 'string', example: 'johndoe'),
                    new OA\Property(property: 'email', type: 'string', format: 'email', example: 'johndoe@example.com'),
                    new OA\Property(property: 'password', type: 'string', format: 'password', example: 'Password123!'),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 201)
        ]

    )]
    public function register(Request $request)
    {
     
        
        $validator = Validator::make($request->all(), [
            'login' => 'required|string|max:255|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|min:10|regex:/^(?=.*[A-Za-z])(?=.*\d).{10,}$/',
            
        ]);

        if ($validator->fails()) {
            abort(INVALID_DATA, 'Informations Invalide');
            
        }


        $validated = $validator->validated();
        User::create([
            'login' => $validated['login'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
        ]);


        return response()->json(['message' => 'Utilisateur créé avec succès'])->setStatusCode(CREATED);

    }

    public function registerAdmin(Request $request)
    {
     
        
        $validator = Validator::make($request->all(), [
            'login' => 'required|string|max:255|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|min:10|regex:/^(?=.*[A-Za-z])(?=.*\d).{10,}$/',
            
            
        ]);

        if ($validator->fails()) {
            abort(INVALID_DATA, 'Informations Invalide');
            
        }


        $validated = $validator->validated();
        User::create([
            'login' => $validated['login'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
            'role_id' => 2
        ]);


        return response()->json(['message' => 'Utilisateur créé avec succès'])->setStatusCode(CREATED);

    }

    #[OA\Post(
        path: '/api/login',
        summary: 'Se connecter',
        description: 'Authentifier un utilisateur et retourner un token d authentification',
        tags: ['Authentification'],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: 'login', type: 'string', example: 'johndoe'),
                    new OA\Property(property: 'password', type: 'string', format: 'password', example: 'Password123!'),
                ]
            )
        ),
        responses: [
            new OA\Response(response: 200)
        ]
    )]

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'login' => 'required|string',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            abort(INVALID_DATA, 'Informations Invalide');
        }

        if (Auth::attempt($request->toArray())) {
            $token = Auth::user()->createToken('auth_token');
            return response()->json($token)->setStatusCode(OK);
        } else {
            abort(UNAUTHORIZED, 'Login ou mot de passe incorrect');
        }


    }

    #[OA\Get(
        path: '/api/me',
        summary: 'Obtenir les informations de l utilisateur connecté',
        description: 'Retourner les informations de l utilisateur actuellement connecté',
        tags: ['Authentification'],
        responses: [
            new OA\Response(response: 200)
        ]
    )]

    public function me()
    {

        return response()->json(Auth::user())->setStatusCode(OK);
    }

    #[OA\Post(
        path: '/api/refresh',
        summary: 'Rafraîchir le token d authentification',
        description: 'Générer un nouveau token d authentification',
        tags: ['Authentification'],
        responses: [
            new OA\Response(response: 200)
        ]
    )]
    public function refresh()
    {
        
        Auth::user()->tokens()->delete();
        $newToken = Auth::user()->createToken('auth_token');
        return response()->json($newToken)->setStatusCode(OK);

    }

    #[OA\Post(
        path: '/api/logout',
        summary: 'Se déconnecter',
        description: 'Supprimer le token d authentification de l utilisateur',
        tags: ['Authentification'],
        responses: [
            new OA\Response(response: 204)
        ]
    )]

    public function logout()
    {

        Auth::user()->tokens()->delete();
        return response()->noContent();
    }

}
