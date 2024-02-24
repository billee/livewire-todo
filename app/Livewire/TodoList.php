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

    public function search(){
        // $todos = Todo::where('name', 'like', '%'.$this->search.'%')->get();
        // $this->resetPage();
        // return view('livewire.todo-list', compact('todos'));
    }


    public function render()
    {
        $todos = Todo::latest()->where('name', 'like', '%'.$this->search.'%')->paginate(5);
        $this->name = '';
        return view('livewire.todo-list', compact('todos'));
    }
}
