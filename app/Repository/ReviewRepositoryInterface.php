<?php

namespace App\Repository;

interface ReviewRepositoryInterface extends RepositoryInterface
{
	// Ajoutez ici des méthodes spécifiques si besoin
	public function reviewAlreadyExists($rentalId, $userId): bool;
}
