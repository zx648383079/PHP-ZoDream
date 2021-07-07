<?php
namespace Module\Shop\Domain\Auction;

use Module\Auth\Domain\FundAccount;
use Module\Auth\Domain\Model\AccountLogModel;
use Module\Auth\Domain\Model\UserModel;
use Module\Shop\Domain\Models\Activity\ActivityModel;
use Module\Shop\Domain\Models\Activity\AuctionLogModel;

abstract class BaseAuction {

    /**
     * 竞拍数据
     * @var AuctionLogModel
     */
    protected $data;

    public function __construct(
        protected ActivityModel $model,
        protected array $configure,
    ) {
    }

    public function setLog($data) {
        $this->data = is_array($data) ? new AuctionLogModel($data) : $data;
        if ($this->data->user_id < 1) {
            $this->data->user_id = auth()->id();
        }
        return $this;
    }

    /**
     * 判断用户是否可以竞拍，包括不能自拍，已是最高出价者，定金不足等
     * @return boolean
     */
    public function isValidUser(): bool {
//        if ($this->data->user_id == $this->model->getGoods()->user_id) {
//            throw new \Exception('不能自己竞拍');
//        }
        $lastLog = $this->maxLog();
        if (!empty($lastLog) && $lastLog->user_id == $this->data->user_id) {
            throw new \Exception('不能连续加价');
        }
        $user = UserModel::findIdentity($this->data->user_id);
        if (empty($user) || $user->money < $this->config('deposit', 0)) {
            throw new \Exception('账户余额不足');
        }
        return true;
    }

    protected function saveToOrder() {
        FundAccount::changeAsync(
            $this->data->user_id, FundAccount::TYPE_SHOPPING, function () {
            $surplus = $this->config('surplus', 1);
            if ($this->data->amount > $surplus) {
                return;
            }
            $surplus -= $this->data->amount;
            $this->model->configure = array_merge($this->config(), [
                'surplus' =>$surplus
            ]);
            if ($surplus <= 0) {
                $this->model->status = ActivityModel::STATUS_END;
            }
            $this->model->save();
            $this->data->status = AuctionLogModel::STATUS_SUCCESS;
            $this->data->save();
        }, - $this->data->bid, sprintf('竞拍[%s]成功', $this->model->name));
    }

    /**
     * 活动结束
     * @return bool
     */
    protected function isActivityEnd(): bool {
        return $this->model->status !== ActivityModel::STATUS_NONE
            || ($this->model->end_at > 0 && $this->model->end_at < time());
    }

    /**
     * @return AuctionLogModel|null
     */
    protected function maxLog() {
       return AuctionLogModel::where('act_id', $this->model->id)
           ->orderBy('bid', 'desc')
           ->first();
    }

    protected function config(string $key = '', $default = null) {
        if (empty($key)) {
            return $this->configure;
        }
        return $this->configure[$key] ?? $default;
    }
}