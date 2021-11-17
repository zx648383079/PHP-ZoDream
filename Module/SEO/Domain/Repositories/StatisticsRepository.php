<?php
declare(strict_types=1);
namespace Module\SEO\Domain\Repositories;

use Module\Auth\Domain\Repositories\StatisticsRepository as Auth;
use Module\Blog\Domain\Repositories\StatisticsRepository as Blog;
use Module\Contact\Domain\Repositories\StatisticsRepository as Contact;


class StatisticsRepository {

    public static function subtotal() {

        return array_merge(
            Auth::subtotal(),
            Blog::subtotal(),
            Contact::subtotal(),
        );
    }
}