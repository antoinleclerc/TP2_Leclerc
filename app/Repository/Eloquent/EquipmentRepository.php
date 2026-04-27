<?php

namespace App\Repository\Eloquent;

use App\Models\Equipment;
use App\Repository\EquipmentRepositoryInterface;

class EquipmentRepository extends BaseRepository implements EquipmentRepositoryInterface
{
	public function __construct(Equipment $model)
	{
		parent::__construct($model);
	}
	// Ajoutez ici des méthodes spécifiques si besoin
}
