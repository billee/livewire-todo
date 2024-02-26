<?php

namespace App\Livewire;

use App\Models\Todo;
use Livewire\Component;
use Livewire\Attributes\Rule;
use Livewire\WithPagination;

class TodoList extends Component
{
    use WithPagination;

    #[Rule('required|min:3|max:5')]
    public $name;

    public $search;

    public function create(){
        $validatedData = $this->validateOnly('name');

        Todo::create($validatedData);
        //$this->reset('name');
        session()->flash('success', 'Created.');
    }

    public function delete(Todo $todo){
        $todo->delete();
        // session()->flash('success', 'Deleted.');
    }

    public function toggle(int $todoId){
        $todo = Todo::find($todoId);
        //dump($todo->completed);
        // $todo->update([
        //     'completed' => 1
        // ]);

        $todo->completed = !$todo->completed;
        $todo->save();
    }

    public function render()
    {
        $todos = Todo::latest()->where('name', 'like', '%'.$this->search.'%')->paginate(5);
        $this->name = '';

        return view('livewire.todo-list', compact('todos'));
    }
}
