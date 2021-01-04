<?php
namespace Module\Shop\Service\Admin\Plugin;


use Module\Shop\Domain\Models\GoodsModel;
use Module\Shop\Service\Admin\Controller;
use Module\SEO\Domain\Model\OptionModel;
use Zodream\ThirdParty\ALi\TaoBaoKe;

class TbkController extends Controller {

    const CODE = 'taobaoke';

    public function indexAction($keywords = null, $page = 1) {
        $data = [];
        if (!empty($keywords)) {
            $data = $this->getApi()->search($keywords, $page);
        }
        return $this->show(compact('data', 'keywords'));
    }

    public function importAction() {
        if (!request()->isPost()) {
            return $this->show();
        }
        $adzone_id = request()->get('adzone_id');
        $data = $this->getApi()->links(
            $adzone_id,
            request()->get('start_time'),
            request()->get('end_time'));
        if (empty($data)) {
            return $this->renderFailure('没有商品');
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
        return $this->renderData($data);
    }



    public function settingAction() {
        if (request()->isPost()) {
            $data = request()->get('option.'.self::CODE);
            OptionModel::insertOrUpdate(self::CODE, Json::encode($data), '淘宝客');
            return $this->renderData([
                'refresh' => true
            ]);
        }
        $data = OptionModel::findCodeJson(self::CODE, [
            'app_key' => '',
            'secret' => ''
        ]);
        return $this->show(compact('data'));
    }

    protected function getApi() {
        $data = OptionModel::findCodeJson(self::CODE);
        return new TaoBaoKe($data);
    }
}