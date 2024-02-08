<?php

namespace App\Data;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Spatie\LaravelData\Data;

class ArchiveMessageFilterData extends Data
{
    public function __construct(
        public bool $enabled,
        public ?string $sender = null,
        public ?string $subject = null,
        public ?string $date = null,
    ) {
    }

    public static function fromRequest(Request $request): static
    {
        $active = $request->boolean('filter');

        return new self(
            $active,
            sender: $active ? $request->input('sender') : null,
            subject: $active ? $request->input('subject') : null,
            date: $active ? $request->input('date') : null,
        );
    }

    public function dates($tz = null)
    {
        if($this->date === null) {
            return  [];
        }
        if (str_contains($this->date, ',')) {
            [$start, $end] = explode(',', $this->date, 2);
            $start = Carbon::parse($start, $tz)->startOfDay();
            $end = Carbon::parse($end, $tz)->endOfDay();
        } else {
            $start = Carbon::parse($this->date, $tz)->startOfDay();
            $end = $start->endOfDay();
        }

        return [$start, $end];
    }

    public function dates_json($format = 'Y-m-d')
    {
        [$start, $end] = $this->dates();

        return [$start->format($format), $end->format($format)];
    }
}
