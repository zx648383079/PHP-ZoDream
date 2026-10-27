<?php
declare(strict_types=1);
namespace Module\Auth\Domain\Advanced;

use Domain\Model\SearchModel;
use Exception;
use Module\Auth\Domain\Advanced\Entities\EquityCardEntity;
use Module\Auth\Domain\Advanced\Entities\UserEquityEntity;
use Zodream\Helpers\Time;

final class CardRepository {

    public static function getList(string $keywords) {
        $page = EquityCardEntity::query()->when(!empty($keywords), function ($query) {
            SearchModel::searchWhere($query, 'name');
        })->orderBy('id', 'desc')->page();
        $page->map(function (EquityCardEntity $item) {
            $item['amount'] = UserEquityEntity::where('card_id', $item->id)
                ->count();
            return $item;
        });
        return $page;
    }

    public static function save(array $data) {
        $id = $data['id'] ?? 0;
        unset($data['id']);
        $model = $id > 0 ? EquityCardEntity::findOrThrow($id) : new EquityCardEntity();
        $model->load($data);
        $model->user_id = auth()->id();
        if (!$model->save()) {
            throw new Exception($model->getFirstError());
        }
        return $model;
    }

    public static function remove(int $id) {
        EquityCardEntity::where('id', $id)->delete();
        UserEquityEntity::where('card_id', $id)->delete();
    }

    public static function userCardList(int $user) {
        return UserEquityEntity::query()->with('card')
            ->where('user_id', $user)
            ->orderBy('expired_at', 'desc')
            ->page();
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
        $model = EquityCardEntity::findOrThrow($card);
        /** @var UserEquityEntity $log */
        $log = UserEquityEntity::where('card_id', $card)->where('user_id', $user)->first();
        $now = time();
        if (empty($log)) {
            UserEquityEntity::create([
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

    public static function search(string $keywords, array|int $id) {
        return EquityCardEntity::query()
            ->when(!empty($id), function ($query) use ($id) {
                if (is_array($id)) {
                    $query->whereIn('id', $id);
                    return;
                }
                $query->where('id', $id);
            })
            ->when(!empty($keywords), function ($query) {
                SearchModel::searchWhere($query, 'name');
            })->select('id', 'name', 'icon')->page();
    }

    public static function userUpdate(array $data) {
        if (!is_numeric($data['expired_at'])) {
            $data['expired_at'] = strtotime($data['expired_at']);
        }
        $data['status'] = $data['expired_at'] > time() ? 1 : 0;
        /** @var UserEquityEntity $log */
        $log = UserEquityEntity::where('user_id', $data['user_id'])
            ->where('card_id', $data['card_id'])
            ->first();
        if (empty($log)) {
            return UserEquityEntity::createOrThrow($data);
        }
        $log->load($data);
        if ($log->save()) {
            // TODO 埋点记录管理账户记录
            return $log;
        }
        throw new Exception($log->getFirstError());
    }

    public static function getUserCard(int $user): array {
        $items = UserEquityEntity::with('card')->where('user_id', $user)
            ->where('status', 1)
            ->where('expired_at', '>', time())
            ->orderBy('card_id', 'desc')->get();
        $data = [];
        foreach ($items as $item) {
            /**@var UserEquityEntity $item  */
            if (!$item->card) {
                continue;
            }
            $data[] = [
                'id' => $item['card_id'],
                'status' => $item['status'],
                'expired_at' => Time::format($item['expired_at']),
                'exp' => $item['exp'],
                'name' => $item->card->name,
                'icon' => $item->card->icon,
            ];
        }
        return $data;
    }
}