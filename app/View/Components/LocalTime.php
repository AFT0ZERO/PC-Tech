<?php

namespace App\View\Components;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\View\Component;

class LocalTime extends Component
{
    private Carbon|null $utcDate = null;
    private static ?string $dbOffset = null;

    public function __construct(
        public Carbon|null $date = null,
        public string $format = 'y-m-d',
        public bool $dateOnly = false,
    ) {
        if ($this->date !== null) {
            $this->utcDate = $this->date->copy()
                ->shiftTimezone($this->getDbOffset())
                ->setTimezone('UTC');
        }
    }

    public function render()
    {
        return view('components.local-time');
    }

    public function iso8601(): string
    {
        if ($this->utcDate === null) {
            return '';
        }

        return $this->utcDate->toIso8601String();
    }

    public function fallback(): string
    {
        if ($this->date === null) {
            return '';
        }

        return $this->date->format($this->format);
    }

    /**
     * Auto-detect the database's current offset from UTC.
     * Cached per request so it is only queried once.
     */
    private function getDbOffset(): string
    {
        if (self::$dbOffset === null) {
            $raw = DB::selectOne('SELECT TIMEDIFF(NOW(), UTC_TIMESTAMP()) AS db_diff')->db_diff;

            // TIMEDIFF returns "HH:MM:SS", possibly with a leading "-"
            $sign = str_starts_with($raw, '-') ? '-' : '+';
            $raw = ltrim($raw, '-');
            [$hours, $minutes] = explode(':', $raw);

            self::$dbOffset = sprintf('%s%02d:%02d', $sign, (int) $hours, (int) $minutes);
        }

        return self::$dbOffset;
    }
}
