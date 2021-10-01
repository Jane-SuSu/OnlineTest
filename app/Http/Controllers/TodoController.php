<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use App\Repositories\TodoRepository;
use App\Repositories\UserRepository;

class TodoController extends BaseController
{
    use AuthorizesRequests;
    use DispatchesJobs;
    use ValidatesRequests;

    protected $todoRepo;

    public function __construct(TodoRepository $todoRepo, UserRepository $userRepo)
    {
        $this->todoRepo = $todoRepo;
        $this->userRepo = $userRepo;
    }

    public function getTodos()
    {
        $email = auth()->user()->email;
        $user = $this->userRepo->getUserByEmail($email);
        $todos = $this->todoRepo->getTodos($user->id);
        
        return response()->json([
            'data' => $todos
        ]);
    }

    public function createTodo(Request $request)
    {
        $email = auth()->user()->email;
        $user = $this->userRepo->getUserByEmail($email);
        $todo = $this->todoRepo->create($user->id, $request->title);

        return response()->json([
            'data' => $todo
        ]);
    }

    public function updateTodo(Request $request)
    {
        $email = auth()->user()->email;
        $user = $this->userRepo->getUserByEmail($email);
        $todo = $this->todoRepo->update($user->id, $request->todoId, $request->title);

        return response()->json([
            'data' => $todo
        ]);
    }

    public function getTodoItems(Request $request)
    {
        $todoId = $request->todoId;
        $todoItems = $this->todoRepo->getTodoItems($todoId);

        return response()->json($todoItems);
    }

    public function deleteTodo(Request $request)
    {
        $todoId = $request->todoId;
        $todoItems = $this->todoRepo->deleteTodo($todoId);

        return response()->json([
            'message' => 'done'
        ]);
    }

    public function createItem(Request $request)
    {
        $item = $this->todoRepo->createItem($request->todoId, $request->content);

        return response()->json([
            'data' => $item
        ]);
    }

    public function updateItem(Request $request)
    {
        $item = $this->todoRepo->updateItem($request->itemId, $request->content);

        return response()->json([
            'data' => $item
        ]);
    }

    public function deleteItem(Request $request)
    {
        $item = $this->todoRepo->deleteItem($request->itemId);

        return response()->json([
            'message' => 'done'
        ]);
    }
}
