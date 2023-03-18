<?php
namespace Module\SEO\Service\Admin;

use Module\SEO\Domain\Model\OptionModel;
use Module\SEO\Domain\Events\OptionUpdated;
use Module\SEO\Domain\Repositories\OptionRepository;
use Zodream\Infrastructure\Contracts\Http\Input as Request;

class SettingController extends Controller {

    public function indexAction() {
        $group_list = OptionModel::where('parent_id', 0)
            ->where('type', 'group')
            ->orderBy('position', 'asc', 'id', 'asc')->all();
        return $this->show(compact('group_list'));
    }

    public function saveAction(Request $request) {
        try {
            OptionRepository::saveNewOption($request->get('field'));
        } catch (\Exception $ex) {}
        OptionRepository::saveOption($request->get('option'));
        event(new OptionUpdated());
        return $this->renderData([
            'refresh' => true
        ]);
    }

    public function infoAction(int $id) {
        return $this->renderData(OptionModel::find($id));
    }

    public function updateAction(Request $request, int $id) {
        try {
            OptionRepository::update($id, $request->get());
        } catch (\Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData([
            'refresh' => true
        ]);
    }

    public function deleteAction(int $id) {
        OptionRepository::remove($id);
        event(new OptionUpdated());
        return $this->renderData([
            'refresh' => true
        ]);
    }
}