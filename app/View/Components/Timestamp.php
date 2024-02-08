<?php

namespace App\View\Components;

use Carbon\CarbonInterface;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Carbon;
use Illuminate\View\Component;

class Timestamp extends Component
{
    public function __construct(
        public Carbon $timestamp,
        public bool $styled = true,
        public bool $relative = true,
    ) {
    }

    public function display(): string
    {
        return $this->relative ? $this->displayRelative() : $this->displayFormatted();
    }

    public function displayFormatted(): string
    {
        if ($this->timestamp->isToday()) {
            return $this->timestamp->format('H:i');
        } else {
            return $this->timestamp->format('m/d');
        }
    }

    public function displayRelative(): string
    {
        if ($this->timestamp->isToday()) {
            if ($this->timestamp->diffInRealMinutes(now()->subRealMinutes(15)) <= 60) {
                return $this->timestamp->diffForHumans(['parts' => 2, 'join' => false, 'short' => true]);
            }

            return 'Today '.$this->timestamp->toTimeString();
        } elseif ($this->timestamp->isYesterday()) {
            return 'Yesterday '.$this->timestamp->toTimeString();
        }
        //        else if ($this->timestamp->isCurrentWeek() || $this->timestamp->isLastWeek()) {
        //            return $this->timestamp->diffForHumans(now(), CarbonInterface::DIFF_RELATIVE_TO_NOW);
        //        }
        elseif ($this->timestamp->isCurrentYear()) {
            return $this->timestamp->format('m/d H:i');
        } else {
            return $this->timestamp->format('m/d/y H:i');
            //            return $this->timestamp->toDateTimeString();
        }
    }

    public function jsDate()
    {
        if ($this->relative) {
            return 'new Intl.RelativeTimeFormat().format(Date.parse(\''.$this->timestamp->toIso8601ZuluString().'\'))';
        } else {

            return 'new Intl.DateTimeFormat().format(Date.parse(\''.$this->timestamp->toIso8601ZuluString().'\'))';
        }
    }

    public function render(): View
    {
        return view('components.timestamp');
    }
}
