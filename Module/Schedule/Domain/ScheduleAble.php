<?php
declare(strict_types=1);
namespace Module\Schedule\Domain;

interface ScheduleAble {

    public function registerSchedule(Scheduler $scheduler): void;

}