<?php

namespace App\Http\Livewire\Navigation;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class NavigationMenuPepAuth extends Component
{
    //
    public $app;
    public $appUserRole;
    public $customers;
    public $customer;
    public $customerRole;
    public $userRole;
    //
    public $organizers;
    public $organizer;
    public $organizerId;
    //
    public $target;
    public $targetRef;
    public $targetId;

    public function updatedOrganizerId()
    {
        sessionClear('organizer');

        $this->organizers = sessionOrganizers();
        $this->organizer  = sessionOrganizer($this->organizerId);

        return redirect()->route('dashboard');
    }

    public function hydrate()
    {
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function mount()
    {
        $this->app          = sessionApp();
        $this->appUserRole  = $this->app->user_role ?? 'user';
        $this->customers    = sessionCustomers();
        $this->customer     = sessionCustomer();
        $this->customerRole = $this->customer->user_role ?? false;
        $this->userRole     = sessionUserRole();
        $this->organizers   = sessionOrganizers();
        $this->organizer    = sessionOrganizer();
        $this->organizerId  = $this->organizer->id ?? false;
        $this->targetRef    = sessionTargetRef();
        $this->targetId     = sessionTargetId();

        // SET REFERER
        sessionReferer();
    }

    public function render()
    {
        return view('livewire.navigation.navigation-menu-pep-auth');
    }
}
