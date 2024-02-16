<?php

namespace App\Repositories;

use Exception;
use PDO;
use PDOException;
use Todo;

class TodoRepository implements TodoRepositoryInterface
{
    private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function getAll(): array
    {
        try {
            $sql = 'SELECT * FROM todos';
            $statement = $this->connection->query($sql);
            $todos = $statement->fetchAll(PDO::FETCH_ASSOC);

            $todoObjects = [];
            foreach ($todos as $todoData) {
                $todoObjects[] = new Todo($todoData['id'], $todoData['title'], $todoData['completed']);
            }

            return $todoObjects;
        } catch (PDOException $e) {
            throw new TodoRepositoryException("Error fetching todos: " . $e->getMessage());
        }
    }

    public function getById(int $id): ?Todo
    {
        try {
            $sql = 'SELECT * FROM todos WHERE id = :id';
            $statement = $this->connection->prepare($sql);
            $statement->bindParam(':id', $id, PDO::PARAM_INT);
            $statement->execute();

            $todoData = $statement->fetch(PDO::FETCH_ASSOC);
            if (!$todoData) {
                return null; // Indicate no todo found
            }

            return new Todo($todoData['id'], $todoData['title'], $todoData['completed']);
        } catch (PDOException $e) {
            throw new TodoRepositoryException("Error fetching todo with ID $id: " . $e->getMessage());
        }
    }

    public function create(Todo $todo): void
    {
        try {
            $sql = 'INSERT INTO todos (title, completed) VALUES (:title, :completed)';
            $statement = $this->connection->prepare($sql);
            $statement->bindParam(':title', $todo->getTitle(), PDO::PARAM_STR);
            $statement->bindParam(':completed', $todo->isCompleted(), PDO::PARAM_BOOL);
            $statement->execute();
        } catch (PDOException $e) {
            throw new TodoRepositoryException("Error creating todo: " . $e->getMessage());
        }
    }

    public function update(Todo $todo): void
    {
        try {
            $sql = 'UPDATE todos SET title = :title, completed = :completed WHERE id = :id';
            $statement = $this->connection->prepare($sql);
            $statement->bindParam(':id', $todo->getId(), PDO::PARAM_INT);
            $statement->bindParam(':title', $todo->getTitle(), PDO::PARAM_STR);
            $statement->bindParam(':completed', $todo->isCompleted(), PDO::PARAM_BOOL);
            $statement->execute();
        } catch (PDOException $e) {
            throw new TodoRepositoryException("Error updating todo with ID {$todo->getId()}: " . $e->getMessage());
        }
    }

    public function delete(int $id): void
    {
        try {
            $sql = 'DELETE FROM todos WHERE id = :id';
            $statement = $this->connection->prepare($sql);
            $statement->bindParam(':id', $id, PDO::PARAM_INT);
            $statement->execute();
        } catch (PDOException $e) {
            throw new TodoRepositoryException("Error deleting todo with ID $id: " . $e->getMessage());
        }
    }
}

interface TodoRepositoryInterface
{
    public function getAll(): array;

    public function getById(int $id): ?Todo;

    public function create(Todo $todo): void;

    public function update(Todo $todo): void;

    public function delete(int $id): void;
}

class TodoRepositoryException extends Exception
{
    // ...
    public function __construct($message, $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
