<?
declare(strict_types=1);
namespace Module\CMS\Service;

use Exception;
use Module\CMS\Domain\Repositories\CMSRepository;
use Module\CMS\Domain\Repositories\FormRepository;
use Zodream\Infrastructure\Contracts\Http\Input;

class FormController extends Controller {

    public function indexAction(Input $input) {
        $model = FormRepository::getModel($input);
        $scene = CMSRepository::scene()->setModel($model);
        $field_list = $scene->fieldList();
        return $this->show(compact('model', 'field_list', 'scene'));
    }

    public function saveAction(Input $input) {
        try {
            FormRepository::save($input);
        } catch (Exception $ex) {
            return $this->renderFailure($ex->getMessage());
        }
        return $this->renderData([
            'url' => './'
        ]);
    }
}