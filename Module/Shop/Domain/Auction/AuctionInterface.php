<?php
namespace Module\Shop\Domain\Auction;
/**
 * 拍卖方式的接口
 * @package Module\Auction\Infrastructure
 */
interface AuctionInterface {

    /**
     * @param $data
     * @return static
     */
    public function setData($data);

    public function hasError();

    /**
     * 根据时间判断是否能拍
     * @return boolean
     */
    public function canAuction();

    /**
     * 判断是否是一个有效的出价
     * @return boolean
     */
    public function isValidPrice();

    /**
     * 判断用户是否可以竞拍，包括不能自拍，已是最高出价者，定金不足等
     * @return boolean
     */
    public function isValidUser();

    /**
     * 获取当前出的价格，没人出价时为0
     * @return float
     */
    public function getPrice();

    /**
     * 竞拍
     * @return boolean
     */
    public function auction();

    /**
     * 生成订单
     * @return mixed
     */
    public function toOrder();
}