<?php
declare(strict_types=1);
namespace Module\Blog\Domain\Repositories;

use Module\Blog\Domain\CCLicenses;
use Module\Blog\Domain\Weather;
use Module\Book\Domain\Repositories\CategoryRepository;

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

    public static function all(): array {
        return [
            'languages' => static::languages(),
            'weathers' => static::weathers(),
            'licenses' => static::licenses(),
            'tags' => TagRepository::get(),
            'categories' => CategoryRepository::all(),
        ];
    }
}