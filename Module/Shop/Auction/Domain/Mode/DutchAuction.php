<?php
namespace Module\Shop\Auction\Domain\Mode;
use Module\Auction\Domain\Model\AuctionLogModel;

/**
 * 荷兰式拍卖即降价拍
 * @package Module\Auction\Domain\Mode
 */
class DutchAuction extends BaseAuction implements AuctionInterface {
    protected $hasPreAuction = false; //是否有预拍

    protected $preTime = 0; //预拍提前时间

    protected $minus_time = 1; // 多少时间减

    protected $minus_price = 1; // 多少钱

    public function setData($data) {
        parent::setData($data);
        if (!$this->isPreAuction()) {
            $this->data->bid = $this->getPrice();
        }
        return $this;
    }

    /**
     * 是否是预拍中
     * @return bool
     */
    public function isPreAuction() {
        $time = time();
        return $this->hasPreAuction
            && $time >= $this->model->start_at - $this->preTime
            && $time < $this->model->start_at;
    }

    /**
     * 根据时间判断是否能拍
     * @return boolean
     */
    public function canAuction() {
        $time = time();
        if ($this->model->status !== AuctionModel::STATUS_NONE) {
            return $this->setError('status', '拍卖结束');
        }
        if ($this->model->end_at > 0 && $this->model->end_at < $time) {
            return false;
        }
        if ($this->hasPreAuction) {
            return $time >= $this->model->start_at - $this->preTime;
        }
        return $time >= $this->model->start_at;
    }

    /**
     * 判断是否是一个有效的数量
     * @return boolean
     */
    public function isValidPrice() {
        return $this->data->number > 0 && $this->data->number <= $this->model->surplus;
    }

    /**
     * 获取当前出的价格，没人出价时为0
     * @return float
     */
    public function getPrice() {
        return $this->model->initial_price -
            (time() - $this->model->start_at) / $this->minus_time * $this->minus_price;
    }

    /**
     * 竞拍
     * @return boolean
     */
    public function auction() {
        if (!$this->canAuction()) {
            return false;
        }
        if ($this->isPreAuction()) {
            return $this->data->save();
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
        $this->saveToOrder();
    }

    /**
     * 生成订单
     * @return mixed
     */
    public function toOrder() {
        $time = time();
        if ($this->model->start_at > $time || $time > $this->model->end_at) {
            return;
        }
        $data = AuctionLogModel::findAll(['where' => [
            'bid >='.$this->getPrice(),
            'number <='.$this->model->surplus, 'status' => AuctionLogModel::STATUS_NONE
        ],
            'order' => 'bid desc, number desc']);
        if (empty($data)) {
            return;
        }
        foreach ($data as $item) {
            $this->data = $item;
            $this->saveToOrder();
        }
    }
}