<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use OpenApi\Attributes as OA;
class RentalController extends Controller
{
    #[OA\Get(
        summary: "Get my active rentals",
        description: "Retrieves all active rentals for the authenticated user",
        responses: [
            new OA\Response(
                response: 200,
                description: "List of active rentals"
            ),
            new OA\Response(
                response: 403,
                description: "Forbidden"
            )
        ]
    )]
    public function myActiveRentals(Request $request)
    {
        
        $user = $request->user();
        if (!$user) {
            return response()->json(['error' => 'Forbidden'], FORBIDDEN);
        }
        $activeRentals = $user->rentals()
            ->where('end_date', '>', now())->orWhere('end_date', null)->where('user_id', $user->id)->get();
        return response()->json($activeRentals, OK);
    }
}
