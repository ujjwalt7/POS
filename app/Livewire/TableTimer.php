<?php

namespace App\Livewire;

use App\Models\Table;
use Livewire\Component;

class TableTimer extends Component
{
    public Table $table;
    public $duration = 0;
    public $formattedDuration = '00:00';

    public function mount(Table $table)
    {
        $this->table = $table;
        $this->updateTimer();
    }

    public function updateTimer()
    {
        if ($this->table->occupied_at && $this->table->available_status === 'running') {
            $this->duration = $this->table->occupied_at->diffInMinutes(now());
            $hours = intval($this->duration / 60);
            $minutes = $this->duration % 60;
            $this->formattedDuration = sprintf('%02d:%02d', $hours, $minutes);
        } else {
            $this->duration = 0;
            $this->formattedDuration = '00:00';
        }
    }

    public function render()
    {
        return view('livewire.table-timer');
    }
}