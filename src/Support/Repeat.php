<?php

namespace MohammedManssour\LaravelRecurringModels\Support;

use MohammedManssour\LaravelRecurringModels\Contracts\Repeatable;
use MohammedManssour\LaravelRecurringModels\Support\PendingRepeats\PendingComplexRepeat;
use MohammedManssour\LaravelRecurringModels\Support\PendingRepeats\PendingEveryMonthRepeat;
use MohammedManssour\LaravelRecurringModels\Support\PendingRepeats\PendingEveryNDaysRepeat;
use MohammedManssour\LaravelRecurringModels\Support\PendingRepeats\PendingEveryWeekRepeat;

class Repeat
{
    protected Repeatable $model;

    public function __construct(Repeatable $model)
    {
        $this->model = $model;
    }

    /**
     * Returns a new instance of PendingEveryNDaysRepeat with the specified number of days.
     *
     * @param int $days The number of days to repeat every
     * @return PendingEveryNDaysRepeat The new instance of PendingEveryNDaysRepeat
     */
    public function everyNDays(int $days): PendingEveryNDaysRepeat
    {
        return new PendingEveryNDaysRepeat($this->model, $days);
    }

    /**
     * Returns a new instance of PendingEveryNDaysRepeat with a repeat interval of 1 day.
     *
     * @return PendingEveryNDaysRepeat The new instance of PendingEveryNDaysRepeat
     */
    public function daily(): PendingEveryNDaysRepeat
    {
        return $this->everyNDays(1);
    }

    /**
     * Returns a new instance of the PendingEveryWeekRepeat class.
     *
     * This method is used to configure a weekly recurrence for a task that needs to be repeated every week.
     * It creates and returns a new instance of the PendingEveryWeekRepeat class, passing $this->model as a parameter.
     * The PendingEveryWeekRepeat class provides methods to further configure the recurrence, such as specifying the day of the week.
     *
     * @return PendingEveryWeekRepeat A new instance of the PendingEveryWeekRepeat class.
     */
    public function weekly(): PendingEveryWeekRepeat
    {
        return new PendingEveryWeekRepeat($this->model);
    }

    /**
     * Returns a new instance of the PendingEveryMonthRepeat class.
     *
     * This method is used to configure a monthly recurrence for a task that needs to be repeated every month.
     * It creates and returns a new instance of the PendingEveryMonthRepeat class, passing $this->model as a parameter.
     * The PendingEveryMonthRepeat class provides methods to further configure the recurrence, such as specifying the day of the month.
     *
     * @return PendingEveryMonthRepeat A new instance of the PendingEveryMonthRepeat class.
     */
    public function monthly(): PendingEveryMonthRepeat{
        return new PendingEveryMonthRepeat($this->model);
    }

    public function yearly(): PendingEveryNDaysRepeat{
        return $this->everyNDays(365);
    }

    public function complex(string $year = '*', string $month = '*', string $day = '*', string $week = '*', string $weekOfMonth = '*', string $weekday = '*'): PendingComplexRepeat
    {
        return (new PendingComplexRepeat($this->model))->rule($year, $month, $day, $week, $weekOfMonth, $weekday);
    }
}
