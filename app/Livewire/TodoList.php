<?php

namespace App\Livewire;

use App\Models\Todo;
use Livewire\Component;
use Livewire\Attributes\Rule;
use Livewire\WithPagination;

class TodoList extends Component{
    use WithPagination;

    #[Rule('required|min:3|max:5')]
    public $name;
    public $search;
    public $editingTodoID;

    #[Rule('required|min:3|max:5')]
    public $editingTodoName;

    public function create(){
        $validatedData = $this->validateOnly('name');

        Todo::create($validatedData);
        session()->flash('success', 'Created.');
        $this->resetPage();
    }

    public function delete(int $todoID){
        try{
            Todo::findOrFail($todoID)->delete();
            //$todo->delete();
            //$this->resetPage();
            session()->flash('success', 'Deleted.');
        }catch(\Exception $e){
            session()->flash('error', 'Cannot delete.');
            return;
        }
    }

    public function toggle(int $todoId){
        $todo = Todo::find($todoId);
        $todo->completed = !$todo->completed;
        $todo->save();
    }

    public function edit(int $todoId){
        $this->editingTodoID = $todoId;
        $this->editingTodoName = Todo::find($todoId)->name;
    }

    public function update(){

        $validatedData = $this->validateOnly('editingTodoName');

        Todo::find($this->editingTodoID)->update([
            'name' =>$validatedData['editingTodoName']
        ]);

        $this->cancelEdit();

        //session()->flash('success', 'Updated.');
    }

    public function cancelEdit(){
        $this->reset('editingTodoID', 'editingTodoName');
    }

    public function render()
    {
        $todos = Todo::latest()->where('name', 'like', '%'.$this->search.'%')->paginate(5);
        $this->name = '';

        return view('livewire.todo-list', compact('todos'));
    }
}
