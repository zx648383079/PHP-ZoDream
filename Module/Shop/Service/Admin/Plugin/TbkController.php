<?php
namespace Module\Shop\Service\Admin\Plugin;

use Module\Shop\Domain\Models\GoodsModel;
use Module\Shop\Domain\Repositories\Plugin\TbkRepository;
use Module\Shop\Service\Admin\Controller;
use Module\SEO\Domain\Model\OptionModel;
use Zodream\Infrastructure\Contracts\Http\Input;
use Zodream\ThirdParty\ALi\TaoBaoKe;

class TbkController extends Controller {

    public function indexAction(string $keywords = '', $page = 1) {
        $data = TbkRepository::search($keywords, $page);
        return $this->show(compact('data', 'keywords'));
    }

    public function importAction(Input $input) {
        if (!$input->isPost()) {
            return $this->show();
        }
        try {
            $data = TbkRepository::import($input->get('adzone_id'),
                $input->get('start_time'), $input->get('end_time'));
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData($data);
    }



    public function settingAction() {
        if (request()->isPost()) {
            $data = request()->get('option.'.self::CODE);
            TbkRepository::saveOption($data);
            return $this->renderData([
                'refresh' => true
            ]);
        }
        $data = TbkRepository::option();
        return $this->show(compact('data'));
    }
}