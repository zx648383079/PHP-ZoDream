<?php
declare(strict_types=1);
namespace Module\Auth\Domain\Repositories;

use Domain\Model\SearchModel;
use Exception;
use Module\Auth\Domain\Model\Card\EquityCardModel;
use Module\Auth\Domain\Model\Card\UserEquityCardModel;

final class CardRepository {

    public static function getList(string $keywords) {
        return EquityCardModel::when(!empty($keywords), function ($query) {
            SearchModel::searchWhere($query, 'name');
        })->orderBy('id', 'desc')->page();
    }

    public static function save(array $data) {
        $id = $data['id'] ?? 0;
        unset($data['id']);
        $model = $id > 0 ? EquityCardModel::findOrThrow($id) : new EquityCardModel();
        $model->load($data);
        $model->user_id = auth()->id();
        if (!$model->save()) {
            throw new Exception($model->getFirstError());
        }
        return $model;
    }

    public static function remove(int $id) {
        EquityCardModel::where('id', $id)->delete();
        UserEquityCardModel::where('card_id', $id)->delete();
    }

    /**
     * 续期权益卡
     * @param int $user
     * @param int $card
     * @param int $second
     * @return void
     * @throws Exception
     */
    public static function recharge(int $user, int $card, int $second) {
        $model = EquityCardModel::findOrThrow($card);
        /** @var UserEquityCardModel $log */
        $log = UserEquityCardModel::where('card_id', $card)->where('user_id', $user)->first();
        $now = time();
        if (empty($log)) {
            UserEquityCardModel::create([
                'user_id' => $user,
                'card_id' => $model->id,
                'status' => 1,
                'expired_at' => $now + $second
            ]);
            return;
        }
        $log->expired_at = max($log->getAttributeSource('expired_at'), $now) + $second;
        $log->status = 1;
        $log->save();
    }
}