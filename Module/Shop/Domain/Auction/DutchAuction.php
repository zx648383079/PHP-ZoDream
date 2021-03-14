<?php
namespace Module\Shop\Domain\Auction;

use Module\Shop\Domain\Models\Activity\AuctionLogModel;
/**
 * 荷兰式拍卖即降价拍
 * @package Module\Auction\Domain\Mode
 */
class DutchAuction extends BaseAuction implements AuctionInterface {
    protected bool $hasPreAuction = false; //是否有预拍

    protected int $preTime = 0; //预拍提前时间

    protected int $minusTime = 1; // 多少时间减

    protected int $minusPrice = 1; // 多少钱

    public function setLog($data) {
        parent::setLog($data);
        if (!$this->isPreAuction()) {
            $this->data->bid = $this->getPrice();
        }
        return $this;
    }

    /**
     * 是否是预拍中
     * @return bool
     */
    public function isPreAuction(): bool {
        $time = time();
        return $this->hasPreAuction
            && $time >= $this->model->start_at - $this->preTime
            && $time < $this->model->start_at;
    }

    /**
     * 根据时间判断是否能拍
     * @return boolean
     */
    public function canAuction(): bool {
        $time = time();
        if ($this->isActivityEnd()) {
            throw new \Exception('拍卖结束');
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
    public function isValidPrice(): bool {
        return $this->data->amount > 0 && $this->data->amount <= $this->config('surplus', 1);
    }

    /**
     * 获取当前出的价格，没人出价时为0
     * @return float
     */
    public function getPrice(): float {
        return $this->config('begin_price') -
            (time() - $this->model->start_at) / $this->minusTime * $this->minusPrice;
    }

    /**
     * 竞拍
     * @return boolean
     */
    public function auction(): bool {
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
        $this->saveToOrder();
        return true;
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
        $data = AuctionLogModel::where('bid', '>=', $this->getPrice())
            ->where('amount', '<=', $this->config('surplus', 1))
            ->where('status', AuctionLogModel::STATUS_NONE)
            ->orderBy(['bid' => 'desc', 'amount' => 'desc'])->get();
        if (empty($data)) {
            return;
        }
        foreach ($data as $item) {
            $this->data = $item;
            $this->saveToOrder();
        }
    }
}