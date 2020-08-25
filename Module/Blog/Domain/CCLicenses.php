<?php
namespace Module\Blog\Domain;

use Zodream\Infrastructure\Support\Html;

class CCLicenses {

    public static function getList() {
        return [
            [
                'name' => 'CC BY',
                'label' => '署名BY',
                'icon' => 'https://mirrors.creativecommons.org/presskit/buttons/88x31/png/by.png',
                'url' => 'https://creativecommons.org/licenses/by/4.0/',
            ],
            [
                'name' => 'CC BY-SA',
                'label' => '署名相同方法同享BY-SA',
                'icon' => 'https://mirrors.creativecommons.org/presskit/buttons/88x31/png/by-sa.png',
                'url' => 'https://creativecommons.org/licenses/by-sa/4.0/',
            ],
            [
                'name' => 'CC BY-NC',
                'label' => '署名非商业性运用BY-NC',
                'icon' => 'https://mirrors.creativecommons.org/presskit/buttons/88x31/png/by-nc.png',
                'url' => 'https://creativecommons.org/licenses/by-nc/4.0/',
            ],
            [
                'name' => 'CC BY-NC-SA',
                'label' => '署名非商业性运用相同方法同享BY-NC-SA',
                'icon' => 'https://mirrors.creativecommons.org/presskit/buttons/88x31/png/by-nc-sa.png',
                'url' => 'https://creativecommons.org/licenses/by-nc-sa/4.0/',
            ],
            [
                'name' => 'CC BY-ND',
                'label' => '署名制止演绎BY-ND',
                'icon' => 'https://mirrors.creativecommons.org/presskit/buttons/88x31/png/by-nd.png',
                'url' => 'https://creativecommons.org/licenses/by-nd/4.0/',
            ],
            [
                'name' => 'CC BY-NC-ND',
                'label' => '署名非商业性运用制止演绎BY-NC-ND',
                'icon' => 'https://mirrors.creativecommons.org/presskit/buttons/88x31/png/by-nc-nd.png',
                'url' => 'https://creativecommons.org/licenses/by-nc-nd/4.0/',
            ],
            [
                'name' => 'CC0',
                'label' => '放弃版权无任何条件0',
                'icon' => 'https://mirrors.creativecommons.org/presskit/buttons/88x31/png/cc-zero.png',
                'url' => 'https://creativecommons.org/publicdomain/zero/1.0/',
            ],
        ];
    }

    public static function render($name) {
        $items = static::getList();
        foreach ($items as $item) {
            if ($name === $item['name']) {
                return Html::a($name, $item['url'], ['target' => '_blank']);
            }
        }
        return $name;
    }
}