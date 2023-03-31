<?php

namespace App\Repositories;

use App\Models\Models\Todo;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

interface TodoRepositoryInterface
{
    /**
     * @return Collection
     */
    public function list(): Collection;

    /**
     * Store new Todo
     *
     * @param array $data
     * @return Todo
     */
    public function store(array $data): Todo;

    /**
     * Show information Todo
     *
     * @param int $id
     * @return Todo
     */
    public function show(int $id): Todo;

    /**
     * Update Todo
     *
     * @param array $data
     * @param int $id
     * @return int 1|0
     */
    public function update(array $data, int $id): int;

    /**
     * Delete one Todo
     *
     * @param Todo $todo
     * @return int 1|0
     */
    public function delete(int $id): int;
}
