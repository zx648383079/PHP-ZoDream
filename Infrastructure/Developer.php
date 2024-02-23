<?php
namespace Infrastructure;

final class Developer {
    public static function author(): array {
        return [
            'name' => 'zodream',
            'avatar' => 'https://zodream.cn/assets/images/zx.jpg',
            'description' => 'Happy everyday!',
            'links' => [
                [
                    'title' => 'GitHub',
                    'icon' => 'fab fa-github',
                    'url' => 'https://github.com/zx648383079',
                ],
                [
                    'title' => 'Email',
                    'icon' => 'fa fa-mail-bulk',
                    'url' => 'mailto:zx648383079@gmail.com',
                ],
                [
                    'title' => 'RSS',
                    'icon' => 'fa fa-rss',
                    'url' => url('/blog/rss'),
                ],
            ]
        ];
    }

    public static function skill(): array {
        return [
            [
                'name' => 'PHP',
                'proficiency' => 70,
                'duration' => '2015-',
                'links' => [
                    [
                        'title' => 'zodream',
                        'url' => 'https://github.com/zodream',
                    ],
                    [
                        'title' => 'PHP-ZoDream',
                        'url' => 'https://github.com/zx648383079/PHP-ZoDream',
                    ],
                ]
            ],
            [
                'name' => 'C#',
                'proficiency' => 60,
                'duration' => '2014-',
                'links' => [
                    [
                        'title' => 'netdream',
                        'url' => 'https://github.com/zx648383079/netdream',
                    ],
                    [
                        'title' => 'ZoDream-Reader',
                        'url' => 'https://github.com/zx648383079/ZoDream-Reader',
                    ],
                    [
                        'title' => 'ZoDream.Spider',
                        'url' => 'https://github.com/zx648383079/ZoDream.Spider',
                    ],
                ]
            ],
            [
                'name' => 'Go',
                'proficiency' => 30,
                'duration' => '2018-',
                'links' => [
                    [
                        'title' => 'godream',
                        'url' => 'https://github.com/zx648383079/godream',
                    ],
                ]
            ],
            [
                'name' => 'Flutter',
                'proficiency' => 40,
                'duration' => '2019-',
                'links' => [
                    [
                        'title' => 'Flutter-Shop',
                        'url' => 'https://github.com/zx648383079/Flutter-Shop',
                    ],
                ]
            ],
            [
                'name' => 'Javascript/Typescript/HTML/CSS/SCSS',
                'proficiency' => 80,
                'duration' => '2015-',
                'links' => [
                    [
                        'title' => 'Angular-ZoDream',
                        'url' => 'https://github.com/zx648383079/Angular-ZoDream',
                    ],
                    [
                        'title' => 'Vue-Shop',
                        'url' => 'https://github.com/zx648383079/Vue-Shop',
                    ],
                    [
                        'title' => 'gulp-vue2mini',
                        'url' => 'https://github.com/zx648383079/gulp-vue2mini',
                    ],
                ]
            ],
            [
                'name' => 'Godot',
                'proficiency' => 20,
                'duration' => '2021-',
            ],
        ];
    }

    public static function formatProficiency(int|float $val): string {
        $level = [
            0 => '入门',
            20 => '初级',
            40 => '中级',
            60 => '高级',
            70 => '精通',
            90 => '专家'
        ];
        $label = current($level);
        foreach ($level as $step => $item) {
            if ($val >= $step) {
                $label = $item;
                continue;
            }
            break;
        }
        return $label;
    }

}