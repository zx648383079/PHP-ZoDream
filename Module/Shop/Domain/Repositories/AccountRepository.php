<?php
declare(strict_types=1);
namespace Module\Shop\Domain\Repositories;

use Module\Auth\Domain\Model\AccountLogModel;
use Module\Auth\Domain\Model\UserModel;
use Module\Shop\Domain\Models\BankCardModel;
use Module\Shop\Domain\Models\CertificationModel;
use Module\Shop\Domain\Models\CouponLogModel;

class AccountRepository {

    public static function logList() {
        return AccountLogModel::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')->page();
    }

    public static function bankCardList() {
        $card_list = BankCardModel::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')->page();
        foreach ($card_list as $item) {
            $item['icon'] = url()->asset('assets/images/wap_logo.png');
        }
        return $card_list;
    }

    public static function certification() {
        return CertificationModel::where('user_id', auth()->id())->first();
    }

    public static function saveCertification(array $data) {
        $cert = static::certification();
        if (empty($cert)) {
            $cert = new CertificationModel();
        }
        $cert->load($data);
        $cert->user_id = auth()->id();
        if (!$cert->save()) {
            throw new \Exception($cert->getFirstError());
        }
        return $cert;
    }

    public static function subtotal() {
        /** @var UserModel $user */
        $user = auth()->user();
        return [
            'money' => $user->money,
            'integral' => $user->credits,
            'bonus' => 0,
            'coupon' => CouponLogModel::where('user_id', $user->id)
                ->where('use_at', 0)->count(),
        ];
    }
}