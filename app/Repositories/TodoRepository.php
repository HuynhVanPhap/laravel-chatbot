<?php

namespace App\Repositories;

use App\Models\Models\Todo;
use App\Repositories\TodoRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class TodoRepository implements TodoRepositoryInterface
{
    /**
     * @var App\Models\Todo
     */
    protected $model;

    public function __construct(Todo $model)
    {
        $this->model = $model;
    }

    /**
     * @inheritDoc
     */
    public function list(): Collection
    {
        return $this->model->get();
    }

    /**
     * @inheritDoc
     */
    public function store(array $data): Todo
    {
        return $this->model->create($data);
    }

    /**
     * @inheritDoc
     */
    public function show(int $id): Todo
    {
        return $this->model->find($id);
    }

    /**
     * @inheritDoc
     */
    public function update(array $data, int $id): int
    {
        $todo = $this->model->find($id);

        return $todo->update($data);
    }

    /**
     * @inheritDoc
     */
    public function delete(int $id): int
    {
        $todo = $this->model->find($id);

        return $todo->delete();
    }
}
