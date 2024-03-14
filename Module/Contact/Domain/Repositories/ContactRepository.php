<?php
declare(strict_types=1);
namespace Module\Contact\Domain\Repositories;

use Infrastructure\LinkRule;
use Module\Auth\Domain\Repositories\BulletinRepository;
use Module\Contact\Domain\Model\FeedbackModel;
use Module\Contact\Domain\Model\FriendLinkModel;
use Module\Contact\Domain\Model\SubscribeModel;

class ContactRepository {

    public static function friendLink(): array {
        return FriendLinkModel::where('status', 1)->asArray()
            ->orderBy('updated_at', 'asc')->get('name', 'logo', 'brief', 'url');
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
        $model = SubscribeModel::query()->where('email', $data['email'])
            ->first();
        if (empty($model)) {
            $model = new SubscribeModel();
        }
        if (!empty($data['name']) && $model->name !== $data['name']) {
            $model->status = 0;
        }
        $model->load($data);
        if (!$model->save()) {
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
        BulletinRepository::system(1, '友情链接申请', '[马上查看]', 98, [
            LinkRule::formatLink('[马上查看]', 'b/friend_link')
        ]);
        return $model;
    }
}