<?php

namespace App\Repository\Eloquent;

use App\Repository\RepositoryInterface;
use Illuminate\Database\Eloquent\Model;

abstract class BaseRepository implements RepositoryInterface
{
	/**
	 * @var Model
	 */
	protected $model;

	public function __construct(Model $model)
	{
		$this->model = $model;
	}

	public function all()
	{
		return $this->model->all();
	}

	public function find($id)
	{
		return $this->model->find($id);
	}

	public function create(array $data)
	{
		return $this->model->create($data);
	}

	public function update($id, array $data)
	{
		$record = $this->model->find($id);
		if ($record) {
			$record->update($data);
		}
		return $record;
	}

	public function delete($id)
	{
		$record = $this->model->find($id);
		if ($record) {
			$record->delete();
		}
		return $record;
	}
}
