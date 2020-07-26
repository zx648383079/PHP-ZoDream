<?php
namespace Module\Shop\Domain\Models\Activity;


use Domain\Model\Model;
use Module\Shop\Domain\Auction\AuctionInterface;
use Module\Shop\Domain\Auction\CommonAuction;
use Module\Shop\Domain\Auction\DutchAuction;
use Module\Shop\Domain\Models\GoodsModel;

/**
 * 拍卖
 * @package Domain\Model\Shopping
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property float $fixed_price 一口价
 * @property float $initial_price 起拍价
 * @property float $plus_price 加价
 * @property float $deposit 保证金
 * @property integer $goods_id
 * @property integer $number 商品数量
 * @property integer $start_at
 * @property integer $end_at
 * @property integer $status  状态
 * @property integer $surplus 剩余数量
 * @property integer $mode
 * @property integer update_at
 * @property integer $create_at
 */
class AuctionModel extends Model {

    const STATUS_NONE = 0;
    const STATUS_END = 1;
    const STATUS_INVALID = 2;// 流拍

    const MODE_COMMON = 0;
    const MODE_DUTCH = 1;

    public static function tableName() {
        return 'auction';
    }

    /**
     * @return GoodsModel
     */
    public function getGoods() {
        return GoodsModel::find($this->goods_id);
    }

    /**
     * @return AuctionLogModel
     */
    public function getMaxLog() {
        return AuctionLogModel::find([
            'where' => ['auction_id' => $this->id],
            'order' => 'bid desc'
        ]);
    }

    /**
     * @return AuctionInterface
     */
    public function getMode() {
        if ($this->mode == self::MODE_DUTCH) {
            return new CommonAuction($this);
        }
        return new DutchAuction($this);
    }

    /**
     * @param $data
     * @return AuctionInterface
     */
    public function auction($data) {
        return $this->getMode()->setData($data);
    }

}