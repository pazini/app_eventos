<?php

namespace App\Http\Livewire;

use App\Models\CustomerOrganizationSub;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class UserProfile extends Component
{
    public function hydrate()
    {
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function mount()
    {
    }

    public function render()
    {
        return view('livewire.user-profile')->layout('layouts.app-pep-auth');
    }
}
