<?php
namespace Module\Shop\Service\Admin\Plugin;


use Module\Shop\Service\Admin\Controller;
use Module\Template\Domain\Model\Base\OptionModel;
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
//        $data = [];
//        if (!empty($keyword)) {
//            $data = $this->getApi()->links($keyword, $page);
//        }
        return $this->show();
    }



    public function settingAction() {
        if (app('request')->isPost()) {
            $data = app('request')->get('option.'.self::CODE);
            OptionModel::insertOrUpdate(self::CODE, Json::encode($data), '淘宝客');
            return $this->jsonSuccess([
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