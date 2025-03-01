<?php

namespace App\Http\Controllers;

use App\Models\Log;
use App\Models\Todo;
use Illuminate\Http\Request;

class TodoController extends Controller
{
    public function fetch()
    {
        $todos = Todo::select('id', 'text', 'status')->where('user_id', auth()->user()->id)->orderBy('created_at', 'DESC')->get();

        return response()->json(['todos' => $todos]);
    }

    public function create(Request $request)
    {
        $request->validate([
            'text' => 'required|max:255',
        ]);

        $todo = Todo::create([
            'user_id' => auth()->user()->id,
            'text' => $request->text,
            'status' => 'ongoing',
        ]);

        Log::create([
            'text' => ucwords(auth()->user()->name) .  ' created a new todo: ' . $request->text . ' status: ' . $request->status . ' datetime: ' . now(),
        ]);

        return response()->json($todo);
    }

    public function destroy(Todo $todo)
    {
        $todo->delete();

        Log::create([
            'text' => ucwords(auth()->user()->name) .  ' deleted todo: ' . $todo->text . ' status: ' . $todo->status . ' datetime: ' . now(),
        ]);

        return response()->json(['status' => 'success']);
    }

    public function complete(Todo $todo)
    {
        $todo->update([
            'status' => 'completed',
        ]);

        Log::create([
            'text' => ucwords(auth()->user()->name) .  ' completed todo: ' . $todo->text . ' status: ' . $todo->status . ' datetime: ' . now(),
        ]);

        return response()->json(['success' => true, 'completed' => $todo->completed]);
    }
}
