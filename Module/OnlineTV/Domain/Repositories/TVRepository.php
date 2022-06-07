<?php
declare(strict_types=1);
namespace Module\OnlineTV\Domain\Repositories;

use Domain\Model\SearchModel;
use Domain\Providers\ActionLogProvider;
use Domain\Providers\CommentProvider;
use Domain\Providers\StorageProvider;
use Domain\Providers\TagProvider;
use Exception;

final class TVRepository {

    const BASE_KEY = 'tv';

    public static function log(): ActionLogProvider {
        return new ActionLogProvider(self::BASE_KEY);
    }

    public static function tag(): TagProvider {
        return new TagProvider(self::BASE_KEY);
    }

    public static function storage(): StorageProvider {
        return StorageProvider::privateStore();
    }
}
