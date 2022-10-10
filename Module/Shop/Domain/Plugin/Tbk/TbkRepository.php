<?php
declare(strict_types=1);
namespace Module\Shop\Domain\Plugin\Tbk;

use Module\SEO\Domain\Model\OptionModel;
use Module\Shop\Domain\Models\GoodsModel;
use Zodream\Helpers\Json;
use Zodream\ThirdParty\ALi\TaoBaoKe;

class TbkRepository {

    const OPTION_CODE = 'taobaoke';

    public static function search(string $keywords, int $page = 1) {
        if (empty($keywords)) {
            return [];
        }
        return static::getApi()->search($keywords, $page);
    }

    public static function option() {
        return OptionModel::findCodeJson(self::OPTION_CODE, [
            'app_key' => '',
            'secret' => ''
        ]);
    }

    public static function isInstalled(): bool {
        $option = static::option();
        return !empty($option) && !empty($option['app_key']) && !empty($option['secret']);
    }

    public static function saveOption(array $data) {
        OptionModel::insertOrUpdate(self::OPTION_CODE, Json::encode($data), '淘宝客');
        return $data;
    }

    public static function import(string $adzone_id, string $start_time, string $end_time) {
        $data = static::getApi()->links($adzone_id, $start_time, $end_time);
        if (empty($data)) {
            throw new \Exception('没有商品');
        }
        foreach ($data as $item) {
            GoodsModel::create([
                'cat_id' => 0,
                'brand_id' => 0,
                'name' => $item['title'],
                'series_number' => sprintf('tbk_%s_%s', $adzone_id, $item['num_iid']),
                'thumb' => $item['pic_url'],
                'picture' => $item['pic_url'],
                'brief' => sprintf('开始时间：%s 结束时间：%s', $item['start_time'], $item['end_time']),
                'content' => $item['click_url'],
                'price' => $item['zk_final_price'],
                'market_price' => $item['reserve_price'],
                'stock' => $item['total_amount'],
            ]);
        }
        return $data;
    }

    protected static function getApi() {
        $data = OptionModel::findCodeJson(self::OPTION_CODE);
        if (empty($data)) {
            throw new \Exception('请先配置');
        }
        return new TaoBaoKe($data);
    }

    public static function statistics() {
        $is_installed = static::isInstalled();
        $income_today = 0;
        $income_count = 0;
        $display_today = 0;
        $display_count = 0;
        $click_count = 0;
        $click_today = 0;
        return compact('is_installed', 'income_today', 'income_count',
            'display_count', 'display_today', 'click_count', 'click_today');
    }
}