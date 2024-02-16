<?php

namespace App\Controllers;

use App\Repositories\TodoRepository;
use Todo;

class TodoController
{
    private TodoRepository $repository;

    public function __construct(TodoRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index(): void
    {
        $todos = $this->repository->getAll();
        require_once 'views/todos/index.php';
    }

    public function create(): void
    {
        require_once 'views/todos/create.php';
    }

    public function edit(int $id): void
    {
        $todo = $this->repository->getById($id);

        if (!$todo) {
            echo "Todo bulunamadı.";
            return;
        }

        require_once 'views/todos/edit.php';
    }

    public function update(int $id, string $title, bool $completed): void
    {
        $todo = new Todo($id, $title, $completed);
        $this->repository->update($todo);

        header('Location: /');
    }

    public function delete(int $id): void
    {
        $this->repository->delete($id);

        header('Location: /');
    }
}
