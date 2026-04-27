<?php

namespace App\Repository\Eloquent;

use App\Models\Review;
use App\Repository\ReviewRepositoryInterface;

class ReviewRepository extends BaseRepository implements ReviewRepositoryInterface
{
	public function __construct(Review $model)
	{
		parent::__construct($model);
	}
	// Ajoutez ici des méthodes spécifiques si besoin
	public function reviewAlreadyExists($rentalId, $userId): bool
	{
		return $this->model->where('rental_id', $rentalId)
			->where('user_id', $userId)
			->exists();
	}
}
