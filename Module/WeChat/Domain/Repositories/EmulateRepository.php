<?php
declare(strict_types=1);
namespace Module\WeChat\Domain\Repositories;

use Domain\Model\SearchModel;
use Module\WeChat\Domain\EmulateResponse;
use Module\WeChat\Domain\Model\MediaModel;
use Module\WeChat\Domain\Model\MenuModel;
use Module\WeChat\Domain\Model\WeChatModel;
use Module\WeChat\Domain\Model\WeChatSimpleModel;
use Module\WeChat\Module;

class EmulateRepository {

    public static function getList(string $keywords = '') {
        return WeChatSimpleModel::when(!empty($keywords), function ($query) {
            SearchModel::searchWhere($query, ['name', 'account']);
        })->page();
    }

    public static function get(int $id) {
        $model = WeChatSimpleModel::find($id);
        if (empty($model)) {
            throw new \Exception('公众号不存在');
        }
        $model->menu_list = MenuModel::with('children')->where('wid', $model->id)->where('parent_id', 0)
            ->get();
        $model->news_list = MediaModel::where('wid', $model->id)->where('type', MediaModel::TYPE_NEWS)
            ->orderBy('created_at', 'desc')->limit(6)
            ->select('id', 'title', 'type', 'media_id', 'parent_id', 'thumb', 'created_at')
            ->get();
        return $model;
    }

    public static function reply(int $id, string $content, string $type = '') {
        $model = WeChatModel::find($id);
        $reply = Module::reply()->setModel($model);
        $reply->setResponse(new EmulateResponse());
        if ($type === 'menu') {
            $reply->replyMenu($content);
        } else {
            $reply->replyMessage($content);
        }
        return $reply->getResponse();
    }

    public static function media(int $id) {
        $model = MediaModel::find($id);
        if (empty($model)) {
            throw new \Exception('资源不存在');
        }
        $model->account = WeChatSimpleModel::find($model->wid);
        return $model;
    }
}