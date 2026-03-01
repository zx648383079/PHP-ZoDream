<?php
declare(strict_types=1);
namespace Module\Team\Domain\Events;


final class DisbandTeam {

    public function __construct(
        public int $teamId,
        public int $timestamp
    ) {
    }
}