<?php

class Todo
{
    private int $id;
    private string $title;
    private bool $completed;

    public function __construct(int $id, string $title, bool $completed)
    {
        $this->id = $id;
        $this->title = $title;
        $this->completed = $completed;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function isCompleted(): bool
    {
        return $this->completed;
    }

    public function setCompleted(bool $completed): void
    {
        $this->completed = $completed;
    }
}

