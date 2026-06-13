<?php

namespace App\Http\Livewire\Modules;

use App\Models\CustomerOrganizationSub;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Livewire\Component;

class ModulePerfil extends Component
{
    public $app;
    public $appModules;
    //
    public $customers;
    public $customer;
    public $customerId;
    //
    public $organizations;
    public $organization;
    public $organizationId;
    //
    public $organizationSubs;
    public $organizationSub;
    public $organizationSubId;
    //
    public $organizers;
    public $organizer;
    public $organizerId;
    //
    public $referer;

    public function updatedOrganizationId()
    {
        $this->organizationSubId = false;
        $this->organizerId = false;
        sessionClear('organization');
        sessionOrganization($this->organizationId);
    }

    public function updatedOrganizationSubId()
    {
        $this->organizerId = false;
        $this->organizationSubs = false;
        sessionClear('organizationSub');
        sessionOrganizationSub($this->organizationSubId);
    }

    public function updatedOrganizerId()
    {
        sessionClear('organizer');
        sessionOrganizers();
        sessionOrganizer($this->organizerId);
    }

    public function updatedCustomerId()
    {
        $this->organizerId = false;
        $this->organizationId = false;
        $this->organizationSubId = false;
        //
        sessionClear('customer');
        sessionCustomer($this->customerId);
    }

    public function resetPerfil()
    {
        // RESET
        sessionClear();
        //
        $this->customerId = false;
        $this->organizationId = false;
        $this->organizationSubId = false;
        $this->organizerId = false;
    }

    public function hydrate()
    {
        $this->resetErrorBag();
        $this->resetValidation();
    }

    public function mount()
    {
        // GET REFERER
        $this->referer = sessionReferer();
    }

    public function render()
    {
        // APP
        $this->app = sessionApp();

        // CUSTOMER
        $this->customers        = sessionCustomers();
        $this->organizations    = sessionOrganizations();
        $this->organizationSubs = sessionOrganizationSubs();
        $this->organizers       = sessionOrganizers();

        //
        if($this->customer = sessionCustomer())
            $this->customerId = $this->customer->id;

        //
        if($this->organization = sessionOrganization())
            $this->organizationId = $this->organization->id;

        //
        if($this->organizationSub = sessionOrganizationSub())
            $this->organizationSubId = $this->organizationSub->id;

        //
        if($this->organizer = sessionOrganizer())
            $this->organizerId = $this->organizer->id;

        // dd(
        //     $this->organizers,
        //     $this->referer,
        // );

        return view('livewire.modules.module-perfil')->layout('layouts.app-pep-auth');
    }
}
