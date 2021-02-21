<?php
declare(strict_types=1);
namespace Module\Shop\Domain\Repositories\Plugin;

use Module\SEO\Domain\Model\OptionModel;
use Module\Shop\Domain\Models\GoodsModel;
use Zodream\Helpers\Json;
use Zodream\ThirdParty\ALi\TaoBaoKe;

class TbkRepository {

    const CODE = 'taobaoke';

    public static function search(string $keywords, int $page = 1) {
        if (empty($keywords)) {
            return [];
        }
        return static::getApi()->search($keywords, $page);
    }

    public static function option() {
        return OptionModel::findCodeJson(self::CODE, [
            'app_key' => '',
            'secret' => ''
        ]);
    }

    public static function saveOption(array $data) {
        OptionModel::insertOrUpdate(self::CODE, Json::encode($data), '淘宝客');
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
        $data = OptionModel::findCodeJson(self::CODE);
        if (empty($data)) {
            throw new \Exception('请先配置');
        }
        return new TaoBaoKe($data);
    }
}