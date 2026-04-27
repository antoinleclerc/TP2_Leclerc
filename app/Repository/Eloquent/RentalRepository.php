<?php

namespace App\Repository\Eloquent;

use App\Models\Rental;
use App\Repository\RentalRepositoryInterface;

class RentalRepository extends BaseRepository implements RentalRepositoryInterface
{
	public function __construct(Rental $model)
	{
		parent::__construct($model);
	}
	// Ajoutez ici des méthodes spécifiques si besoin
}
