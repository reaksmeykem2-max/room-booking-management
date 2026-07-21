<?php

namespace App\Livewire\Admin;

use App\Models\Holiday;
use Livewire\Component;
use Livewire\WithPagination;

class ManageHolidays extends Component
{
    use WithPagination;

    public $date = '';
    public $name = '';
    public $description = '';
    public $is_recurring = false;
    public $showForm = false;
    public $editingId = null;
    public $message = '';
    public $messageType = 'success';

    protected $rules = [
        'date' => 'required|date',
        'name' => 'required|min:2|max:100',
    ];

    public function openCreateForm()
    {
        $this->resetForm();
        $this->showForm = true;
    }

    public function editHoliday($id)
    {
        $holiday = Holiday::findOrFail($id);
        $this->editingId = $id;
        $this->date = $holiday->date->format('Y-m-d');
        $this->name = $holiday->name;
        $this->description = $holiday->description ?? '';
        $this->is_recurring = $holiday->is_recurring;
        $this->showForm = true;
    }

    public function saveHoliday()
    {
        $this->validate();

        $data = [
            'date' => $this->date,
            'name' => $this->name,
            'description' => $this->description ?: null,
            'is_recurring' => $this->is_recurring,
        ];

        if ($this->editingId) {
            Holiday::findOrFail($this->editingId)->update($data);
            $this->message = 'Holiday updated!';
        } else {
            Holiday::create($data);
            $this->message = 'Holiday added!';
        }

        $this->messageType = 'success';
        $this->showForm = false;
        $this->resetForm();
    }

    public function deleteHoliday($id)
    {
        Holiday::findOrFail($id)->delete();
        $this->message = 'Holiday removed.';
        $this->messageType = 'success';
    }

    public function resetForm()
    {
        $this->editingId = null;
        $this->date = '';
        $this->name = '';
        $this->description = '';
        $this->is_recurring = false;
    }

    public function closeForm()
    {
        $this->showForm = false;
        $this->resetForm();
    }

    public function render()
    {
        $holidays = Holiday::orderBy('date')->paginate(15);

        return view('livewire.admin.manage-holidays', [
            'holidays' => $holidays,
        ]);
    }
}
