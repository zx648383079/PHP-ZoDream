<?php
namespace Module\Shop\Domain\Auction;

use Module\Auth\Domain\Model\UserModel;
use Module\Shop\Domain\Models\Activity\AuctionLogModel;
use Module\Shop\Domain\Models\Activity\AuctionModel;

abstract class BaseAuction {

    /**
     * 拍卖数据
     * @var AuctionModel
     */
    protected $model;

    /**
     * 竞拍数据
     * @var AuctionLogModel
     */
    protected $data;

    public function __construct(AuctionModel $model) {
        $this->model = $model;
    }

    public function setData($data) {
        $this->data = is_array($data) ? (new AuctionLogModel())->load($data) : $data;
        if (empty($this->data->user_id)) {
            $this->data->user_id = auth()->id();
        }
        return $this;
    }

    /**
     * 判断用户是否可以竞拍，包括不能自拍，已是最高出价者，定金不足等
     * @return boolean
     */
    public function isValidUser() {
//        if ($this->data->user_id == $this->model->getGoods()->user_id) {
//            throw new \Exception('不能自己竞拍');
//        }
        $lastLog = $this->model->getMaxLog();
        if (!empty($lastLog) && $lastLog->user_id == $this->data->user_id) {
            throw new \Exception('不能连续加价');
        }
        $user = UserModel::findIdentity($this->data->user_id);
        if (empty($user) || $user->money < $this->model->deposit) {
            throw new \Exception('账户余额不足');
        }
        return true;
    }

    protected function saveToOrder() {
        if ($this->data->number > $this->model->surplus) {
            return;
        }
        $this->model->surplus -= $this->data->number;
        if ($this->model->surplus <= 0) {
            $this->model->status = AuctionModel::STATUS_END;
        }
        $this->model->save();
        $this->data->status = AuctionLogModel::STATUS_SUCCESS;
        $this->data->save();
        $user = UserModel::findIdentity($this->data->user_id);
        $user->money -= $this->model->deposit;
        $user->save();
    }
}