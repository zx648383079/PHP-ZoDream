<?php
namespace Module\Shop\Domain\Auction;

use Module\Shop\Domain\Models\Activity\ActivityModel;

/**
 * 普通拍卖方式即加价拍
 * @package Module\Auction\Domain\Mode
 */
class CommonAuction extends BaseAuction implements AuctionInterface {

    /**
     * 根据时间判断是否能拍
     * @return boolean
     * @throws \Exception
     */
    public function canAuction(): bool {
        $time = time();
        if ($this->model->start_at > $time || $time >= $this->model->end_at) {
            throw new \Exception('拍卖无效');
        }
        if ($this->isActivityEnd()) {
            throw new \Exception('拍卖结束');
        }
        return true;
    }

    /**
     * 判断是否是一个有效的出价
     * @return bool
     */
    public function isValidPrice(): bool {
        $current = $this->getPrice();
        if ($current == 0) {
            return $this->data->bid >= $this->config('begin_price');
        }
        return $this->data->bid
            >= $current + $this->config('step_price');
    }

    /**
     * 获取当前价格
     * @return float
     */
    public function getPrice(): float {
        $lastLog = $this->maxLog();
        if (empty($lastLog)) {
            return 0;
        }
        return $lastLog->bid;
    }

    /**
     * 竞拍
     * @return boolean
     * @throws \Exception
     */
    public function auction(): bool {
        if (!$this->canAuction()) {
            return false;
        }
        if (!$this->isValidPrice()) {
            return false;
        }
        if (!$this->isValidUser()) {
            return false;
        }
        $this->data->amount = $this->config('surplus', 1);
        if (!$this->data->save()) {
            throw new \Exception('保存失败');
        }
        if ($this->config('fixed_price') > 0
            && $this->data->bid >= $this->config('fixed_price')) {
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
        if ($this->model->status === ActivityModel::STATUS_NONE) {
            return;
        }
        $this->data = $this->maxLog();
        if (empty($this->data)) {
            // 流拍
            $this->model->status = ActivityModel::STATUS_INVALID;
        }
        $this->saveToOrder();
    }
}