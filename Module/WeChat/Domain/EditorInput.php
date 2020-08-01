<?php
namespace Module\WeChat\Domain;

use Module\WeChat\Domain\Editors\Event;
use Module\WeChat\Domain\Editors\InputInterface;
use Module\WeChat\Domain\Editors\Media;
use Module\WeChat\Domain\Editors\Mini;
use Module\WeChat\Domain\Editors\News;
use Module\WeChat\Domain\Editors\Scene;
use Module\WeChat\Domain\Editors\Template;
use Module\WeChat\Domain\Editors\Text;
use Module\WeChat\Domain\Editors\Url;
use Module\WeChat\Domain\Model\EditorModel;
use Module\WeChat\Domain\Model\MenuModel;
use Module\WeChat\Domain\Scene\BindingScene;
use Module\WeChat\Domain\Scene\CheckInScene;
use Module\WeChat\Domain\Scene\ZaJinHuaScene;
use Zodream\Infrastructure\Http\Request;
use Zodream\ThirdParty\WeChat\MenuItem;
use Zodream\ThirdParty\WeChat\MessageResponse;

class EditorInput {
    const TYPE_TEXT = 0;
    const TYPE_MEDIA = 1;
    const TYPE_NEWS = 2;
    const TYPE_TEMPLATE = 3;
    const TYPE_EVENT = 4;
    const TYPE_URL = 5;
    const TYPE_MINI = 6;
    const TYPE_SCENE = 7;

    public static $scene_list = [
        BindingScene::class => '账号绑定',
        CheckInScene::class => '每日签到',
        ZaJinHuaScene::class => '炸金花',
    ];

    public static $type_list = [
        self::TYPE_TEXT => '文本',
        self::TYPE_MEDIA => '媒体素材',
        self::TYPE_NEWS => '图文',
        self::TYPE_TEMPLATE => '模板消息',
        self::TYPE_EVENT => '事件',
        self::TYPE_URL => '网址',
        self::TYPE_MINI => '小程序',
        self::TYPE_SCENE => '场景'
    ];

    public static $type_class = [
        self::TYPE_TEXT => Text::class,
        self::TYPE_MEDIA => Media::class,
        self::TYPE_NEWS => News::class,
        self::TYPE_TEMPLATE => Template::class,
        self::TYPE_EVENT => Event::class,
        self::TYPE_URL => Url::class,
        self::TYPE_MINI => Mini::class,
        self::TYPE_SCENE => Scene::class
    ];

    /**
     * 缓存实例
     * @var array
     */
    private static $instanceItems = [];

    /**
     * @param $type
     * @return InputInterface
     * @throws \Exception
     */
    public static function instance($type) {
        $type = intval($type);
        if (isset(self::$instanceItems[$type])) {
            return self::$instanceItems[$type];
        }
        $name = isset(static::$type_class[$type]) ? static::$type_class[$type] : '';
        if (empty($name) || !class_exists($name)) {
            throw new \Exception('editor input error');
        }
        return self::$instanceItems[$type] = new $name();
    }

    public static function form(EditorModel $model) {
        return static::instance($model->type)->form($model);
    }

    public static function save(EditorModel $model, Request $request) {
        return static::instance($model->type)->save($model, $request);
    }

    public static function render(EditorModel $model, MessageResponse $response) {
        return static::instance($model->type)->render($model, $response);
    }

    public static function renderMenu(MenuModel $model, MenuItem $menu) {
        return static::instance($model->type)->renderMenu($model, $menu);
    }

    public static function invoke($type, $action, Request $request, $controller) {
        $instance = static::instance($type);
        if (method_exists($instance, $action)) {
            return $instance->$action($request, $controller);
        }
        throw new \Exception('action error');
    }

    public static function typeItems(EditorModel $model) {
        return static::$type_list;
    }
}