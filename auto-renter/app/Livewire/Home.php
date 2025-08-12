<?php

namespace App\Livewire;
use Livewire\Component;
use Livewire\Attributes\Layout;

#[Layout('components.layouts.app.header')]
class Home extends Component
{
    public function render()
    {
        return view('livewire.home');
    }
}
