<?php

namespace App\Http\Controllers;
use App\Repository\ReviewRepositoryInterface;
use Illuminate\Http\Request;
use Exception;
use OpenApi\Attributes as OA;
class ReviewController extends Controller
{
    private ReviewRepositoryInterface $reviewRepository;
    public function __construct(ReviewRepositoryInterface $reviewRepository)
    {
        $this->reviewRepository = $reviewRepository;
    }
    #[OA\Post(
        summary: "Create a new review",
        description: "Creates a new review for the authenticated user",
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: "application/json",
                schema: new OA\Schema(
                    ref: "#/components/schemas/Review"
                )
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: "Review created successfully"
            ),
            new OA\Response(
                response: 403,
                description: "Forbidden"
            ),
            new OA\Response(
                response: 422,
                description: "Unprocessable Entity"
            )
        ]
    )]
    public function store(Request $request)
    {
        try {
            $user = $request->user();
            if (!$user) {
                return response()->json(['error' => 'Forbidden'], FORBIDDEN);
            }
            $rentalId = $request->input('rental_id');
            if ($this->reviewRepository->reviewAlreadyExists($rentalId, $user->id)) {
                return response()->json(['error' => 'Vous avez déjà laissé une critique pour cette location.'], 422);
            }
            $data = $request->all();
            $data['user_id'] = $user->id;
            $review = $this->reviewRepository->create($data);
            return response()->json($review, CREATED);
        } catch (Exception $ex) {
            return response()->json(['error' => 'Server error'], SERVER_ERROR);
        }
    }

    
}
