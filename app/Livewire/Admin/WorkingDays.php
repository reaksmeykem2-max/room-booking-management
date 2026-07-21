<?php

namespace App\Livewire\Admin;

use App\Models\WorkingSchedule;
use Livewire\Component;

class WorkingDays extends Component
{
    public $schedules = [];
    public $message = '';
    public $messageType = 'success';

    public function mount()
    {
        $this->loadSchedules();
    }

    public function loadSchedules()
    {
        $this->schedules = WorkingSchedule::orderBy('day_of_week')
            ->get()
            ->map(function ($schedule) {
                return [
                    'id' => $schedule->id,
                    'day_of_week' => $schedule->day_of_week,
                    'day_name' => $schedule->day_name,
                    'start_time' => substr($schedule->start_time, 0, 5),
                    'end_time' => substr($schedule->end_time, 0, 5),
                    'is_working_day' => $schedule->is_working_day,
                ];
            })
            ->toArray();
    }

    public function toggleWorkingDay($index)
    {
        $this->schedules[$index]['is_working_day'] = !$this->schedules[$index]['is_working_day'];
    }

    public function saveSchedules()
    {
        foreach ($this->schedules as $scheduleData) {
            WorkingSchedule::where('id', $scheduleData['id'])->update([
                'start_time' => $scheduleData['start_time'] . ':00',
                'end_time' => $scheduleData['end_time'] . ':00',
                'is_working_day' => $scheduleData['is_working_day'],
            ]);
        }

        $this->message = 'Working schedule updated!';
        $this->messageType = 'success';
    }

    public function render()
    {
        return view('livewire.admin.working-days');
    }
}
