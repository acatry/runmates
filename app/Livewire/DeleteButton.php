<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Database\Eloquent\Model;

class DeleteButton extends Component
{
    public Model $model;
    public bool $confirm = false;
    public string $redirect;

    public function confirmDelete()
    {
        $this->confirm = true;
    }

    public function delete()
    {
        $this->model->delete();

        session()->flash('message', 'Supprimé avec succès.');

        return redirect()->to($this->redirect);
    }

    public function render()
    {
        return view('livewire.delete-button');
    }
}
