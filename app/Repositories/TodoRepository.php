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

    public function getTodos($userId)
    {
        $todos = $this->todo
            ->select(['id', 'user_id', 'title', 'attachment', 'created_at', 'updated_at'])
            ->with('items')
            ->where('user_id', $userId)
            ->get();

        return $todos->toArray();
    }

    public function create($userId, $title)
    {
        $todo = new Todo;
        $todo->user_id = $userId;
        $todo->title = $title;
        $todo->save();

        $todo = $this->todo
            ->where('id', $todo->id)
            ->first();

        return $todo->toArray();
    }

    public function update($userId, $todoId, $title)
    {
        $todo = $this->todo
            ->where('id', $todoId)
            ->where('user_id', $userId)
            ->first();
        
        $todo->title = $title;
        $todo->save();

        return $todo->toArray();
    }

    public function getTodoItems($todoId)
    {
        $todo = $this->todo
            ->select(['id', 'user_id', 'title', 'attachment', 'created_at', 'updated_at'])
            ->where('id', $todoId)
            ->with('items')
            ->first();

        return $todo->toArray();
    }

    public function deleteTodo($todoId)
    {
        $this->item
            ->where('todo_id', $todoId)
            ->delete();

        $this->todo
            ->where('id', $todoId)
            ->delete();
    }

    public function createItem($todoId, $content)
    {
        $item = new TodoItem;
        $item->todo_id = $todoId;
        $item->content = $content;
        $item->save();

        $item = $this->item
            ->where('id', $item->id)
            ->first();

        return $item->toArray();
    }

    public function updateItem($itemId, $content)
    {
        $item = $this->item
            ->where('id', $itemId)
            ->first();
        $item->content = $content;
        $item->save();

        return $item->toArray();
    }

    public function deleteItem($itemId)
    {
        $this->item
            ->where('id', $itemId)
            ->delete();
    }
}
