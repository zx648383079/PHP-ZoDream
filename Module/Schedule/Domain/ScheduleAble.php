<?php
namespace Module\Schedule\Domain;

interface ScheduleAble {

    public function registerSchedule(Scheduler $scheduler);

}