<?php
//define('DEBUG', true);
//define('APP_DIR', dirname(dirname(__FILE__)));
//require_once(APP_DIR.'/vendor/autoload.php');

use Zodream\Image\Node\CircleNode;
use Zodream\Service\Application;
use Zodream\Image\Node\BoxNode;
use Zodream\Image\Node\ImgNode;
use Zodream\Image\Node\TextNode;
use Zodream\Image\Node\BorderNode;

require_once dirname(__DIR__).'/Service/Bootstrap.php';
$app = new Application(APP_DIR);

//config([
//    'image.driver' => 'imagick'
//]);

$bg = ImgNode::create(public_path('assets/images/sd_bg.png'), [
    'width' => '100%',
    'height' => '100%'
]);
$box = BoxNode::create([
    'font' => (string)app_path()->file('data/fonts/YaHei.ttf'),
    'padding' => '60,30',
    'background' => $bg,
    'width' => 626,
    'color' => '#000'
])->append(
    BoxNode::create([
        'radius' => 10,
        'padding' => '50,10,100',
        'background' => '#fff',
    ])->append(
        BoxNode::create()
            ->append(
                CircleNode::create(60, 60, 60, null, '#ccc', 1)
                ->style('position', 'fixed'),
                CircleNode::create(60, 60, 56,
                    ImgNode::create(public_path('assets/images/avatar/0.png'))
                )->style('position', 'fixed'),
                TextNode::create('张三', [
                    'size' => 30,
                    'y' => 35,
                    'x' => 130,
                    'bold' => true
                ]),
                TextNode::create('上海科技公司 - 总经理', [
                    'size' => 16,
                    'margin-top' => 10,
                    'x' => 130,
                ])
            ),
        BoxNode::create([
            'margin' => '50,40,0',
        ])->append(
            TextNode::create('正在招聘', [
                'size' => 10,
                'color' => '#777'
            ]),
            TextNode::create('高级工程师', [
                'size' => 30,
                'margin-top' => 20,
                'bold' => true
            ]),
            TextNode::create('上海徐汇东 | 3-5年 | 大专 | 10-20k', [
                'size' => 16,
                'margin-top' => 20,
                'bold' => true,
                'color' => '#777'
            ]),
            TextNode::create('职位详情', [
                'size' => 16,
                'margin-top' => 50,
            ]),
            TextNode::create('1、负责公司微服务', [
                'size' => 14,
                'margin-top' => 20,
                'lineSpace' => 14,
                'wrap' => true,
                'color' => '#777'
            ]),
            TextNode::create('2、负责公司微服务', [
                'size' => 14,
                'margin-top' => 20,
                'lineSpace' => 14,
                'wrap' => true,
                'color' => '#777'
            ]),
            TextNode::create('岗位要求', [
                'size' => 16,
                'margin-top' => 50,
            ]),
            TextNode::create('1、负责公司微服务', [
                'size' => 14,
                'margin-top' => 20,
                'lineSpace' => 14,
                'wrap' => true,
                'color' => '#777'
            ]),
            TextNode::create('2、负责公司微服务', [
                'size' => 14,
                'margin-top' => 20,
                'lineSpace' => 14,
                'wrap' => true,
                'color' => '#777'
            ])
                )
            ),

    BoxNode::create([
        'margin-top' => 10,
        'color' => '#000',
    ])->append(
        ImgNode::create(public_path('assets/images/avatar/0.png'), [
            'x' => 60,
            'y' => 60,
            'width' => 174,
            'height' => 174,
            'position' => 'fixed'
        ]),
        TextNode::create('猿人聘', [
            'size' => 30,
            'margin-left' => 50,
            'margin-top' => 30
        ]),
        BorderNode::create(
            TextNode::create('长按识别二维码', [
                'size' => 16,
                'padding' => '10,5',
            ]), [
            'margin-top' => 15,
            'x' => 250,
            'radius' => 5,
        ]),
        TextNode::create('进入猿人聘查看职位详情', [
            'size' => 16,
            'margin-top' => 15,
            'x' => 250,
        ])
            ),
    BoxNode::create([
        'margin-top' => 15,
        'center' => true,
    ])->append([
        ImgNode::create(public_path('assets/images/avatar/0.png'), [
            'x' => 200,
            'width' => 50,
            'height' => 50,
            'position' => 'fixed'
        ]),
        TextNode::create('猿人聘', [
            'size' => 40,
            'margin-left' => 15,
            'margin-top' => 15,
            'color' => '#000'
        ]),
    ])
        );
$box->draw()->show()
;
dd($t);