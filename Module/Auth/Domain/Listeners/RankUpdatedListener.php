<?php
declare(strict_types=1);
namespace Module\Auth\Domain\Listeners;

use Module\Auth\Domain\Advanced\RankRepository;
use Module\Auth\Domain\Events\RankUpdated;

final class RankUpdatedListener {

    public function __construct(RankUpdated $event) {
        if (!AUTH_ADVANCED) {
            return;
        }
        RankRepository::update($event->getUser(), $event->getValue());
    }
}