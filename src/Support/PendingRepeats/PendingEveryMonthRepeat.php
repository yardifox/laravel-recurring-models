<?php
namespace MohammedManssour\LaravelRecurringModels\Support\PendingRepeats;

use Illuminate\Support\Collection;
use MohammedManssour\LaravelRecurringModels\Contracts\Repeatable;
use MohammedManssour\LaravelRecurringModels\Enums\RepetitionType;
use MohammedManssour\LaravelRecurringModels\Exceptions\RepetitionEndsAfterNotAvailableException;

class PendingEveryMonthRepeat extends PendingRepeat{
    /**
     * days
     *
     * @var Collection<integer, object>
     */
    private Collection $days;

    /**
     * weeks
     *
     * @var Collection<integer, object>
     */
    private Collection $weeks;

    private Collection $rules;

    public function __construct(Repeatable $model){
        parent::__construct($model);
        $this->days = collect([]);
        $this->weeks = collect([]);
        $this->weeks = collect([]);
    }

    /**
     * Sets the days to be used for filtering.
     *
     * @param array $days The days to be used for filtering.
     * @return static Returns an instance of the class with the updated days.
     */
    public function on(array $days): static{
        $this->days = collect($this->monthdays())
            ->intersect($days)
            ->values();

        return $this;
    }

    public function endsAfter(int $times): static{
        throw new RepetitionEndsAfterNotAvailableException();
    }

    public function rules(): array{
        if($this->rules->isEmpty()) {
            $this->makeRules();
        }

        return $this->rules->toArray();
    }

    private function makeRules(): void{
        if($this->days->isEmpty()) {
            $this->rules->push(
                $this->getRule(
                    $this->model->repetitionBaseDate(RepetitionType::Simple)->toImmutable()->addMonthNoOverflow()->startOfMonth()
                )
            );
        } else {
            $this->days->each(function($day){
                $this->rules->push(
                    $this->getRule(
                        $this->model->repetitionBaseDate(RepetitionType::Simple)->toImmutable()->addMonthNoOverflow()->startOfMonth()->next($day)
                    )
                );
            });
        }
    }

    private function getRule(string $day): array{
        $complexPattern = (new PendingComplexRepeat($this->model))
            ->rule(weekday: array_search($day, $this->weekdays()),weekOfMonth: $this->weekOfMonth($day));
        if ($this->end_at) {
            $complexPattern->endsAt($this->end_at);
        }

        $rules = $complexPattern->rules();

        return $rules[0];
    }

    public function __destruct(){
        $this->rules();
    }

    private function weekdays(){
        return ['sunday','monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
    }

    private function monthdays(){
        return range(1, 31);
    }

    private function weekOfMonth(string $day){
        $date = $this->model->repetitionBaseDate(RepetitionType::Simple)->toImmutable()->addMonthNoOverflow()->startOfMonth()->next($day);
        return $date->weekOfMonth;
    }
}
