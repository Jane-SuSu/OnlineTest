<?php

namespace App\Repositories;

use App\Models\Todo;
use App\Models\TodoItem;

class TodoRepository
{
    protected $todo;
    protected $todoItem;

    public function __construct(Todo $todo, TodoItem $item)
    {
        $this->todo = $todo;
        $this->item = $item;
    }

    public function getTodos($userId): array
    {
        $todos = $this->todo
            ->select(['id', 'user_id', 'title', 'attachment', 'created_at', 'updated_at'])
            ->with('items')
            ->where('user_id', $userId)
            ->get();

        return $todos->toArray();
    }

    public function create($userId, $title) : array
    {
        $todo = new Todo();
        $todo->user_id = $userId;
        $todo->title = $title;
        $todo->save();

        $todo = $this->todo
            ->where('id', $todo->id)
            ->firstOrFail();

        return $todo->toArray();
    }

    public function update($userId, $todoId, $title): array
    {
        $todo = $this->todo
            ->where('id', $todoId)
            ->where('user_id', $userId)
            ->firstOrFail();
        
        $todo->title = $title;
        $todo->save();

        return $todo->toArray();
    }

    public function getTodoItems($todoId): array
    {
        $todo = $this->todo
            ->select(['id', 'user_id', 'title', 'attachment', 'created_at', 'updated_at'])
            ->where('id', $todoId)
            ->with('items')
            ->firstOrFail();

        return $todo->toArray();
    }

    public function deleteTodo($todoId): void
    {
        $this->item
            ->where('todo_id', $todoId)
            ->delete();

        $this->todo
            ->where('id', $todoId)
            ->delete();
    }

    public function createItem($todoId, $content): array
    {
        $item = new TodoItem();
        $item->todo_id = $todoId;
        $item->content = $content;
        $item->save();

        $item = $this->item
            ->where('id', $item->id)
            ->firstOrFail();

        return $item->toArray();
    }

    public function updateItem($itemId, $content): array
    {
        $item = $this->item
            ->where('id', $itemId)
            ->firstOrFail();
        $item->content = $content;
        $item->save();

        return $item->toArray();
    }

    public function deleteItem($itemId): void
    {
        $this->item
            ->where('id', $itemId)
            ->delete();
    }
}
