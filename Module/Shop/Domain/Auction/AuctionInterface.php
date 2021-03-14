<?php
declare(strict_types=1);
namespace Module\Shop\Domain\Auction;
/**
 * 拍卖方式的接口
 * @package Module\Auction\Infrastructure
 */
interface AuctionInterface {

    /**
     * 设置当前竞拍记录
     * @param $data
     * @return static
     */
    public function setLog($data);

    /**
     * 根据时间判断是否能拍
     * @return boolean
     */
    public function canAuction(): bool;

    /**
     * 判断是否是一个有效的出价
     * @return boolean
     */
    public function isValidPrice(): bool;

    /**
     * 判断用户是否可以竞拍，包括不能自拍，已是最高出价者，定金不足等
     * @return boolean
     */
    public function isValidUser(): bool;

    /**
     * 获取当前出的价格，没人出价时为0
     * @return float
     */
    public function getPrice(): float;

    /**
     * 竞拍
     * @return boolean
     */
    public function auction(): bool;

    /**
     * 生成订单
     * @return mixed
     */
    public function toOrder();
}