<?php
namespace Module\Auction\Domain\Mode;

use Domain\Model\Auth\UserModel;
use Module\Auction\Domain\Model\AuctionLogModel;
use Module\Auction\Domain\Model\AuctionModel;
use Zodream\Infrastructure\Traits\ErrorTrait;
use Zodream\Service\Factory;

abstract class BaseAuction {

    use ErrorTrait;
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
        $this->clearError();
        $this->data = is_array($data) ? (new AuctionLogModel())->load($data) : $data;
        if (empty($this->data->user_id)) {
            $this->data->user_id = Factory::user()->getId();
        }
        return $this;
    }

    /**
     * 判断用户是否可以竞拍，包括不能自拍，已是最高出价者，定金不足等
     * @return boolean
     */
    public function isValidUser() {
        if ($this->data->user_id == $this->model->getGoods()->user_id) {
            return $this->setError('user_id', '不能自己竞拍');
        }
        $lastLog = $this->model->getMaxLog();
        if (!empty($lastLog) && $lastLog->user_id == $this->data->user_id) {
            return $this->setError('user_id', '不能连续加价');
        }
        $user = UserModel::findOne($this->data->user_id);
        if ($user->money < $this->model->deposit) {
            return $this->setError('user_id', '账户余额不足不');
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
        $user = UserModel::findOne($this->data->user_id);
        $user->money -= $this->model->deposit;
        $user->save();
    }
}