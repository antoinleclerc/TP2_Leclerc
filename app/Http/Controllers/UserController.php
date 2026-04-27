<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repository\UserRepositoryInterface;
use exception;
use Illuminate\Validation\ValidationException;
use OpenApi\Attributes as OA;
class UserController extends Controller
{
    private UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    #[OA\Put(
        summary: "Update user password",
        description: "Updates the password of the authenticated user",
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                required: true,
                description: "ID of the user to update"
            )
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: "application/json",
                schema: new OA\Schema(
                    type: "object",
                    properties: [
                        new OA\Property(
                            property: "password",
                            type: "string",
                            description: "New password for the user"
                        ),
                        new OA\Property(
                            property: "password_confirmation",
                            type: "string",
                            description: "Confirmation of the new password"
                        )
                    ]
                )
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Password updated successfully"
            ),
            new OA\Response(
                response: 403,
                description: "Forbidden"
            ),
            new OA\Response(
                response: 404,
                description: "User not found"
            ),
            new OA\Response(
                response: 422,
                description: "Validation error"
            ),
            new OA\Response(
                response: 500,
                description: "Server error"
            )
        ]
    )]
    public function update(Request $request, $id)
    {
        
        $authUser = $request->user();
        if (!$authUser) {
            return response()->json(['error' => 'Forbidden'], FORBIDDEN);
        }
        try {
            $request->validate([
                'password' => 'required|confirmed|min:10|regex:/^(?=.*[A-Za-z])(?=.*\d).{10,}$/'
            ]);

            $user = $this->userRepository->find($id);
            if (!$user) {
                return response()->json(['message' => 'User not found'], NOT_FOUND);
            }

            $this->userRepository->update($id, [
                'password' => bcrypt($request->password)
            ]);

            return response()->json(['message' => 'Password updated successfully'], OK);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], INVALID_DATA);
        } catch (Exception $e) {
            return response()->json(['message' => 'Server error'], SERVER_ERROR);
        }
    }
}
