<?php
declare(strict_types=1);
namespace Module\Contact\Domain\Repositories;

use Module\Auth\Domain\Model\Bulletin\BulletinModel;
use Module\Contact\Domain\Model\FeedbackModel;
use Module\Contact\Domain\Model\FriendLinkModel;
use Module\Contact\Domain\Model\SubscribeModel;

class ContactRepository {

    public static function friendLink(): array {
        return FriendLinkModel::where('status', 1)->asArray()->orderBy('updated_at', 'asc')->get();
    }

    /**
     * @param array $data
     * @return FeedbackModel
     * @throws \Exception
     */
    public static function saveFeedback(array $data): FeedbackModel {
        $model = new FeedbackModel();
        $model->load($data);
        $model->user_id = auth()->id();
        if (!$model->save()) {
            throw new \Exception($model->getFirstError());
        }
        return $model;
    }

    public static function saveSubscribe(array $data): SubscribeModel {
        $model = new SubscribeModel();
        if (!$model->load($data) || !$model->save()) {
            throw new \Exception($model->getFirstError());
        }
        return $model;
    }

    public static function unsubscribe(string $email): bool {
        SubscribeModel::where('email', $email)->update([
            'status' => 1,
            'updated_at' => time()
        ]);
        return true;
    }

    public static function applyFriendLink(array $data): FriendLinkModel {
        $model = new FriendLinkModel();
        $model->load($data);
        $model->user_id = auth()->id();
        if (!$model->save()) {
            throw new \Exception($model->getFirstError());
        }
        BulletinModel::system(1, '友情链接申请',
            sprintf('<a href="%s">马上查看</a>', url('./@admin/friend_link')), 98);
        return $model;
    }
}