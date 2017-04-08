<?php
namespace Module\Auction\Domain\Mode;

use Module\Auction\Domain\Model\AuctionModel;

/**
 * 普通拍卖方式即加价拍
 * @package Module\Auction\Domain\Mode
 */
class CommonAuction extends BaseAuction implements AuctionInterface {

    /**
     * 根据时间判断是否能拍
     * @return boolean
     */
    public function canAuction() {
        $time = time();
        if ($this->model->start_at > $time || $time >= $this->model->end_at) {
            return $this->setError('time', '拍卖无效');
        }
        if ($this->model->status !== AuctionModel::STATUS_NONE) {
            return $this->setError('status', '拍卖结束');
        }
        return true;
    }

    /**
     * 判断是否是一个有效的出价
     * @return bool
     */
    public function isValidPrice() {
        $current = $this->getPrice();
        if ($current == 0) {
            return $this->data->bid >= $this->model->initial_price;
        }
        return $this->data->bid
            >= $current + $this->model->plus_price;
    }

    /**
     * 获取当前价格
     * @return float
     */
    public function getPrice() {
        $lastLog = $this->model->getMaxLog();
        if (empty($lastLog)) {
            return 0;
        }
        return $lastLog->bid;
    }

    /**
     * 竞拍
     * @return boolean
     */
    public function auction() {
        if (!$this->canAuction()) {
            return false;
        }
        if (!$this->isValidPrice()) {
            return false;
        }
        if (!$this->isValidUser()) {
            return false;
        }
        if ($this->hasError()) {
            return false;
        }
        $this->data->number = $this->model->number;
        if (!$this->data->save()) {
            return $this->setError('save', '保存失败');
        }
        if ($this->model->fixed_price > 0
            && $this->data->bid >= $this->model->fixed_price) {
            // 一口价竞拍成功
            $this->saveToOrder();
        }
        return true;
    }

    /**
     * 使用定时器检查并生成订单
     * @return mixed
     */
    public function toOrder() {
        if ($this->model->end_at > time()) {
            //拍卖未结束
            return;
        }
        if ($this->model->status == AuctionModel::STATUS_NONE) {
            return;
        }
        $this->data = $this->model->getMaxLog();
        if (empty($this->data)) {
            // 流拍
            $this->model->status = AuctionModel::STATUS_INVALID;
        }
        $this->saveToOrder();
    }
}