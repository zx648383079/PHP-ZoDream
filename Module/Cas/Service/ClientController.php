<?php
namespace Module\Cas\Service;

use Module\Cas\Domain\Model\ClientTicketModel;
use Zodream\Domain\Access\Auth;
use Zodream\Http\Http;
use Zodream\Service\Factory;
use Zodream\Service\Routing\Url;

class ClientController extends Controller {

    protected function getServerUrl($path, $params = []) {
        return Url::to('./server/'.$path, $params);
    }

    public function indexAction($ticket = null) {
        if (empty($ticket)) {
            return $this->redirect($this->getServerUrl('login', [
                'service' => (string)Url::to('./client')
            ]));
        }
        if (!$this->validate($ticket)) {
            return;
        }
        ClientTicketModel::create([
            'ticket' => $ticket,
            'session_id' => Factory::session()->id()
        ]);
        return $this->redirect('/');
    }

    /**
     * 服务端注销通知调用
     * @param $ticket
     * @return \Zodream\Infrastructure\Http\Response
     * @throws \Exception
     */
    public function logoutAction($ticket) {
        $model = ClientTicketModel::findByTicket($ticket);
        Factory::session()->destroySession($model->session_id);
        return $this->jsonSuccess();
    }

    protected function validate($ticket) {
        $uri = $this->getServerUrl('validate', [
            'service' => (string)Url::to('./client'),
            'ticket' => $ticket
        ]);
        $data = (new Http($uri))->json();
        if ($data['code'] != 200) {
            return false;
        }
        $userClass = Config::auth('model');
        if (empty($userClass)) {
            return $this->jsonFailure('user is error!', 401);
        }
        /** @var UserModel $user */
        $user = call_user_func($userClass.'::findByIdentity', $data['data']['user_id']);
        Auth::login($user);
        return true;
    }
}