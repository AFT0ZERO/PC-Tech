<?php

namespace App\View\Components;

use Carbon\Carbon;
use Illuminate\View\Component;

class LocalTime extends Component
{
    public function __construct(
        public Carbon|null $date = null,
        public string $format = 'y-m-d',
        public bool $dateOnly = false,
    ) {
    }

    public function render()
    {
        return view('components.local-time');
    }

    public function iso8601(): string
    {
        if ($this->date === null) {
            return '';
        }

        return $this->date->toIso8601String();
    }

    public function fallback(): string
    {
        if ($this->date === null) {
            return '';
        }

        return $this->date->format($this->format);
    }
}
