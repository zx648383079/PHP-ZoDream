<?php
declare(strict_types=1);
namespace Module\Blog\Domain\Repositories;

use Domain\Repositories\LocalizeRepository;
use Module\Blog\Domain\CCLicenses;
use Module\Blog\Domain\Weather;

class OptionRepository {

    public static function languages(): array {
        return [
            'Html', 'Css', 'Sass', 'Less', 'TypeScript', 'JavaScript', 'PHP', 'Go', 'C#', 'ASP.NET', '.NET Core', 'Python', 'C', 'C++', 'Java', 'Kotlin', 'Swift', 'Objective-C', 'Dart', 'Flutter'
        ];
    }

    public static function weathers(): array {
        $items = [];
        $data = Weather::getList();
        foreach ($data as $value => $name) {
            $items[] = compact('name', 'value');
        }
        return $items;
    }

    public static function licenses(): array {
        return array_map(function ($item) {
            return [
                'name' => $item['label'],
                'value' => $item['name']
            ];
        }, CCLicenses::getList());
    }

    public static function openItems() {
        return [
            ['name' => __('Is Public'), 'value' => PublishRepository::OPEN_PUBLIC],
            ['name' => __('Need Login'), 'value' => PublishRepository::OPEN_LOGIN],
            ['name' => __('Need Password'), 'value' => PublishRepository::OPEN_PASSWORD],
            ['name' => __('Need Buy'), 'value' => PublishRepository::OPEN_BUY],
        ];
    }

    public static function publishItems() {
        return [
            ['name' => __('As a draft'), 'value' => PublishRepository::PUBLISH_STATUS_DRAFT],
            ['name' => __('As a publish'), 'value' => PublishRepository::PUBLISH_STATUS_POSTED],
            ['name' => __('As a trash'), 'value' => PublishRepository::PUBLISH_STATUS_TRASH],
        ];
    }

    public static function all(): array {
        return [
            'localizes' => LocalizeRepository::languageOptionItems(),
            'languages' => static::languages(),
            'weathers' => static::weathers(),
            'licenses' => static::licenses(),
            'tags' => TagRepository::get(),
            'categories' => CategoryRepository::all(),
            'open_types' => static::openItems(),
            'publish_status' => static::publishItems()
        ];
    }
}