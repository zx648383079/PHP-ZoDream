<?php
namespace Module\Game\CheckIn\Domain\Repositories;


use Module\Game\CheckIn\Domain\Model\CheckInModel;
use Module\OpenPlatform\Domain\Platform;

class CheckinRepository {

    /**
     * @return null|CheckInModel
     * @throws \Exception
     */
    public static function today() {
        if (auth()->guest()) {
            return null;
        }
        return CheckInModel::today()->where('user_id', auth()->id())->first();
    }

    public static function check($method = CheckInModel::METHOD_WEB) {
        if (auth()->guest()) {
            throw new \Exception('请先登录', 401);
        }
        $model = CheckInModel::checkIn(auth()->id(), $method);
        if (empty($model)) {
            throw new \Exception('签到失败');
        }
        return $model;
    }

    public static function canCheckIn(): bool {
        if (auth()->guest()) {
            return false;
        }
        return CheckInModel::canCheckIn(auth()->id());
    }

    public static function monthLog(string $month = '') {
        $timestamp = empty($month) ? time() : strtotime($month);
        return CheckInModel::month($timestamp)->where('user_id', auth()->id())->get();
    }

    public static function formatMethod(): int {
        /** @var Platform $platform */
        $platform = app('platform');
        if (empty($platform)) {
            return CheckInModel::METHOD_WEB;
        }
        return intval($platform->type());
    }
}