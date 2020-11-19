<?php
namespace Module\Cas\Service;

use Module\Cas\Domain\Client;
use Module\Cas\Domain\Model\ClientTicketModel;

use Zodream\Service\Config;
use Zodream\Service\Factory;

class ClientController extends Controller {

    public function indexAction($ticket = null) {
        if (empty($ticket)) {
            return $this->redirect(Client::getServerLoginURL());
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
        if (!Client::handleLogoutRequests()) {
            return $this->renderFailure('IP error');
        }
        $model = ClientTicketModel::findByTicket($ticket);
        Factory::session()->destroySession($model->session_id);
        return $this->renderData();
    }

    protected function validate($ticket) {
        $userClass = Config::auth('model');
        if (empty($userClass)) {
            return $this->renderFailure('user is error!', 401);
        }
        $id = Client::validate($ticket);
        if (empty($id)) {
            return false;
        }
        /** @var UserModel $user */
        $user = call_user_func($userClass.'::findByIdentity', $id);
        auth()->login($user);
        return true;
    }
}