<?php
declare(strict_types=1);
namespace Module\SEO\Domain\Listeners;

use Module\SEO\Domain\Option;

class OptionUpdatedListener {
    public function __construct($event) {
        Option::getInstance()->clearCache();
        cache()->flush();
    }
}