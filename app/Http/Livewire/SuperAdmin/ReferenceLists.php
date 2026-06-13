<?php

namespace App\Http\Livewire\SuperAdmin;

use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Component;

class ReferenceLists extends Component
{
    public $activeTab = 'states';

    public $states = [];
    public $types = [];
    public $categories = [];

    public $stateForm = [
        'ref_slug' => '',
        'ref_value' => '',
        'ref_label' => '',
        'to_view' => true,
    ];

    public $typeForm = [
        'ref_slug' => '',
        'ref_value' => '',
        'ref_label' => '',
        'to_view' => true,
    ];

    public $categoryForm = [
        'ref_slug' => '',
        'ref_value' => '',
        'ref_label' => '',
        'to_view' => true,
    ];

    public $editingStateId = null;
    public $editingTypeId = null;
    public $editingCategoryId = null;

    public $stateEdit = [
        'ref_slug' => '',
        'ref_value' => '',
        'ref_label' => '',
        'to_view' => true,
    ];

    public $typeEdit = [
        'ref_slug' => '',
        'ref_value' => '',
        'ref_label' => '',
        'to_view' => true,
    ];

    public $categoryEdit = [
        'ref_slug' => '',
        'ref_value' => '',
        'ref_label' => '',
        'to_view' => true,
    ];

    public function mount()
    {
        $this->loadAll();
    }

    public function render()
    {
        return view('livewire.super-admin.reference-lists')
            ->layout('layouts.app-pep-auth');
    }

    public function setTab(string $tab)
    {
        $this->activeTab = $tab;
    }

    public function loadAll()
    {
        $this->states = DB::table('ref_app_states')->orderBy('ref_slug')->get();
        $this->types = DB::table('ref_app_event_type')->orderBy('ref_slug')->get();
        $this->categories = DB::table('ref_app_event_category')->orderBy('ref_slug')->get();
    }


    public function createState()
    {
        $data = $this->validate([
            'stateForm.ref_slug' => ['required', 'string', 'max:255', 'unique:ref_app_states,ref_slug'],
            'stateForm.ref_value' => ['required', 'string', 'max:255'],
            'stateForm.ref_label' => ['nullable', 'string', 'max:255'],
            'stateForm.to_view' => ['boolean'],
        ]);

        $payload = $data['stateForm'];
        $payload['ref_slug'] = strtolower($payload['ref_slug']);
        $payload['ref_label'] = $payload['ref_label'] ?: $payload['ref_value'];

        DB::table('ref_app_states')->insert([
            'ref_slug' => $payload['ref_slug'],
            'ref_value' => $payload['ref_value'],
            'ref_label' => $payload['ref_label'],
            'to_view' => $payload['to_view'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->reset('stateForm');
        $this->stateForm['to_view'] = true;
        $this->loadAll();
        $this->emit('notify', 'Estado criado com sucesso.', 'success');
    }

    public function editState(int $id)
    {
        $state = DB::table('ref_app_states')->where('id', $id)->first();

        if (!$state) {
            return;
        }

        $this->editingStateId = $id;
        $this->stateEdit = [
            'ref_slug' => $state->ref_slug,
            'ref_value' => $state->ref_value,
            'ref_label' => $state->ref_label,
            'to_view' => (bool) $state->to_view,
        ];
    }

    public function updateState()
    {
        if (!$this->editingStateId) {
            return;
        }

        $data = $this->validate([
            'stateEdit.ref_slug' => [
                'required',
                'string',
                'max:255',
                Rule::unique('ref_app_states', 'ref_slug')->ignore($this->editingStateId),
            ],
            'stateEdit.ref_value' => ['required', 'string', 'max:255'],
            'stateEdit.ref_label' => ['nullable', 'string', 'max:255'],
            'stateEdit.to_view' => ['boolean'],
        ]);

        $payload = $data['stateEdit'];
        $payload['ref_slug'] = strtolower($payload['ref_slug']);
        $payload['ref_label'] = $payload['ref_label'] ?: $payload['ref_value'];

        DB::table('ref_app_states')
            ->where('id', $this->editingStateId)
            ->update([
                'ref_slug' => $payload['ref_slug'],
                'ref_value' => $payload['ref_value'],
                'ref_label' => $payload['ref_label'],
                'to_view' => $payload['to_view'],
                'updated_at' => now(),
            ]);

        $this->editingStateId = null;
        $this->stateEdit = $this->stateForm;
        $this->loadAll();
        $this->emit('notify', 'Estado atualizado com sucesso.', 'success');
    }

    public function cancelEditState()
    {
        $this->editingStateId = null;
        $this->stateEdit = $this->stateForm;
    }

    public function deleteState(int $id)
    {
        DB::table('ref_app_states')->where('id', $id)->delete();

        if ($this->editingStateId === $id) {
            $this->cancelEditState();
        }

        $this->loadAll();
        $this->emit('notify', 'Estado removido com sucesso.', 'success');
    }

    public function createType()
    {
        $data = $this->validate([
            'typeForm.ref_slug' => ['required', 'string', 'max:255', 'unique:ref_app_event_type,ref_slug'],
            'typeForm.ref_value' => ['required', 'string', 'max:255'],
            'typeForm.ref_label' => ['nullable', 'string', 'max:255'],
            'typeForm.to_view' => ['boolean'],
        ]);

        $payload = $data['typeForm'];
        $payload['ref_slug'] = strtolower($payload['ref_slug']);
        $payload['ref_label'] = $payload['ref_label'] ?: $payload['ref_value'];

        DB::table('ref_app_event_type')->insert([
            'ref_slug' => $payload['ref_slug'],
            'ref_value' => $payload['ref_value'],
            'ref_label' => $payload['ref_label'],
            'to_view' => $payload['to_view'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->reset('typeForm');
        $this->typeForm['to_view'] = true;
        $this->loadAll();
        $this->emit('notify', 'Tipo criado com sucesso.', 'success');
    }

    public function editType(int $id)
    {
        $type = DB::table('ref_app_event_type')->where('id', $id)->first();

        if (!$type) {
            return;
        }

        $this->editingTypeId = $id;
        $this->typeEdit = [
            'ref_slug' => $type->ref_slug,
            'ref_value' => $type->ref_value,
            'ref_label' => $type->ref_label,
            'to_view' => (bool) $type->to_view,
        ];
    }

    public function updateType()
    {
        if (!$this->editingTypeId) {
            return;
        }

        $data = $this->validate([
            'typeEdit.ref_slug' => [
                'required',
                'string',
                'max:255',
                Rule::unique('ref_app_event_type', 'ref_slug')->ignore($this->editingTypeId),
            ],
            'typeEdit.ref_value' => ['required', 'string', 'max:255'],
            'typeEdit.ref_label' => ['nullable', 'string', 'max:255'],
            'typeEdit.to_view' => ['boolean'],
        ]);

        $payload = $data['typeEdit'];
        $payload['ref_slug'] = strtolower($payload['ref_slug']);
        $payload['ref_label'] = $payload['ref_label'] ?: $payload['ref_value'];

        DB::table('ref_app_event_type')
            ->where('id', $this->editingTypeId)
            ->update([
                'ref_slug' => $payload['ref_slug'],
                'ref_value' => $payload['ref_value'],
                'ref_label' => $payload['ref_label'],
                'to_view' => $payload['to_view'],
                'updated_at' => now(),
            ]);

        $this->editingTypeId = null;
        $this->typeEdit = $this->typeForm;
        $this->loadAll();
        $this->emit('notify', 'Tipo atualizado com sucesso.', 'success');
    }

    public function cancelEditType()
    {
        $this->editingTypeId = null;
        $this->typeEdit = $this->typeForm;
    }

    public function deleteType(int $id)
    {
        DB::table('ref_app_event_type')->where('id', $id)->delete();

        if ($this->editingTypeId === $id) {
            $this->cancelEditType();
        }

        $this->loadAll();
        $this->emit('notify', 'Tipo removido com sucesso.', 'success');
    }

    public function createCategory()
    {
        $data = $this->validate([
            'categoryForm.ref_slug' => ['required', 'string', 'max:255', 'unique:ref_app_event_category,ref_slug'],
            'categoryForm.ref_value' => ['required', 'string', 'max:255'],
            'categoryForm.ref_label' => ['nullable', 'string', 'max:255'],
            'categoryForm.to_view' => ['boolean'],
        ]);

        $payload = $data['categoryForm'];
        $payload['ref_slug'] = strtolower($payload['ref_slug']);
        $payload['ref_label'] = $payload['ref_label'] ?: $payload['ref_value'];

        DB::table('ref_app_event_category')->insert([
            'ref_slug' => $payload['ref_slug'],
            'ref_value' => $payload['ref_value'],
            'ref_label' => $payload['ref_label'],
            'to_view' => $payload['to_view'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->reset('categoryForm');
        $this->categoryForm['to_view'] = true;
        $this->loadAll();
        $this->emit('notify', 'Categoria criada com sucesso.', 'success');
    }

    public function editCategory(int $id)
    {
        $category = DB::table('ref_app_event_category')->where('id', $id)->first();

        if (!$category) {
            return;
        }

        $this->editingCategoryId = $id;
        $this->categoryEdit = [
            'ref_slug' => $category->ref_slug,
            'ref_value' => $category->ref_value,
            'ref_label' => $category->ref_label,
            'to_view' => (bool) $category->to_view,
        ];
    }

    public function updateCategory()
    {
        if (!$this->editingCategoryId) {
            return;
        }

        $data = $this->validate([
            'categoryEdit.ref_slug' => [
                'required',
                'string',
                'max:255',
                Rule::unique('ref_app_event_category', 'ref_slug')->ignore($this->editingCategoryId),
            ],
            'categoryEdit.ref_value' => ['required', 'string', 'max:255'],
            'categoryEdit.ref_label' => ['nullable', 'string', 'max:255'],
            'categoryEdit.to_view' => ['boolean'],
        ]);

        $payload = $data['categoryEdit'];
        $payload['ref_slug'] = strtolower($payload['ref_slug']);
        $payload['ref_label'] = $payload['ref_label'] ?: $payload['ref_value'];

        DB::table('ref_app_event_category')
            ->where('id', $this->editingCategoryId)
            ->update([
                'ref_slug' => $payload['ref_slug'],
                'ref_value' => $payload['ref_value'],
                'ref_label' => $payload['ref_label'],
                'to_view' => $payload['to_view'],
                'updated_at' => now(),
            ]);

        $this->editingCategoryId = null;
        $this->categoryEdit = $this->categoryForm;
        $this->loadAll();
        $this->emit('notify', 'Categoria atualizada com sucesso.', 'success');
    }

    public function cancelEditCategory()
    {
        $this->editingCategoryId = null;
        $this->categoryEdit = $this->categoryForm;
    }

    public function deleteCategory(int $id)
    {
        DB::table('ref_app_event_category')->where('id', $id)->delete();

        if ($this->editingCategoryId === $id) {
            $this->cancelEditCategory();
        }

        $this->loadAll();
        $this->emit('notify', 'Categoria removida com sucesso.', 'success');
    }
}
