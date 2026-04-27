<?php

namespace App\Repository\Eloquent;

use App\Models\Sport;
use App\Repository\SportRepositoryInterface;

class SportRepository extends BaseRepository implements SportRepositoryInterface
{
	public function __construct(Sport $model)
	{
		parent::__construct($model);
	}
	
}
