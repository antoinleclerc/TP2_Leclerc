<?php

namespace App\Http\Controllers;

use App\Repository\EquipmentRepositoryInterface;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use OpenApi\Attributes as OA;
class EquipmentController extends Controller
{
    private EquipmentRepositoryInterface $equipmentRepository;
    public function __construct(EquipmentRepositoryInterface $equipmentRepository)
    {
        $this->equipmentRepository = $equipmentRepository;
    }
    
    #[OA\Post(
        summary: "Create a new equipment",
        description: "Creates a new equipment with the provided details",
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: "application/json",
                schema: new OA\Schema(
                    ref: "#/components/schemas/Equipment"
                )
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: "Equipment created successfully"
            ),
            new OA\Response(
                response: 403,
                description: "Forbidden"
            )
        ]
    )]
    public function store(Request $request)
    {
        $user = $request->user();
        if (!$user || $user->role_id != 2) {
            return response()->json(['error' => 'Forbidden: admin only'], FORBIDDEN);
        }
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'daily_price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
        ]);
        try {
            $equipment = $this->equipmentRepository->create($validated);
            return response()->json($equipment, CREATED);
        } catch (Exception $ex) {
            return response()->json(['error' => 'Server error'], SERVER_ERROR);
        }
    }

    #[OA\Put(
        summary: "Update an existing equipment",
        description: "Updates an existing equipment with the provided details",
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: "application/json",
                schema: new OA\Schema(
                    ref: "#/components/schemas/Equipment"
                )
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Equipment updated successfully"
            ),
            new OA\Response(
                response: 403,
                description: "Forbidden"
            ),
            new OA\Response(
                response: 404,
                description: "Equipment not found"
            )
        ]
    )]
    public function update(Request $request, $id)
    {
        $user = $request->user();
        if (!$user || $user->role_id != 2) {
            return response()->json(['error' => 'Forbidden: admin only'], FORBIDDEN);
        }
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'daily_price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
        ]);
        try {
            $equipment = $this->equipmentRepository->update($id, $validated);
            return response()->json($equipment, OK);
        } catch (ModelNotFoundException $ex) {
            return response()->json(['error' => 'Equipment not found'], NOT_FOUND);
        } catch (QueryException $ex) {
            return response()->json(['error' => 'Cannot be updated in database'], INVALID_DATA);
        } catch (Exception $ex) {
            return response()->json(['error' => 'Server error'], SERVER_ERROR);
        }
    }

        #[OA\Delete(
            summary: "Delete an equipment",
            description: "Deletes an equipment by its ID",
            responses: [
                new OA\Response(
                    response: 200,
                    description: "Equipment deleted successfully"
                ),
                new OA\Response(
                    response: 403,
                    description: "Forbidden"
                ),
                new OA\Response(
                    response: 404,
                    description: "Equipment not found"
                )
            ]
        )]
    public function destroy(Request $request, $id)
    {
        $user = $request->user();
        if (!$user || $user->role_id != 2) {
            return response()->json(['error' => 'Forbidden: admin only'], FORBIDDEN);
        }
        try {
            $this->equipmentRepository->delete($id);
            return response()->json(['message' => 'Equipment deleted'], OK);
        } catch (ModelNotFoundException $ex) {
            return response()->json(['error' => 'Equipment not found'], NOT_FOUND);
        } catch (Exception $ex) {
            return response()->json(['error' => 'Server error'], SERVER_ERROR);
        }
    }
}
    

