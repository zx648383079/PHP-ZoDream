<?php
namespace Module\Cas\Service;

use Module\Cas\Domain\Model\PGTicketModel;
use Module\Cas\Domain\Model\ServiceModel;
use Module\Cas\Domain\Model\TicketModel;

use Zodream\Http\Uri;

class ServerController extends Controller {

    public function loginAction($service) {
        $url = new Uri($service);
        $serviceModel = ServiceModel::findByUrl($url);
        if (empty($serviceModel)) {
            return $this->redirectWithMessage();
        }
        if (auth()->guest()) {
            // 登录
            return $this->redirectWithAuth();
        }
        TicketModel::where('service_url', $service)->delete();
        $model = new TicketModel([
            'service_id' => $serviceModel->id,
            'service_url' => $service,
            'user_id' => auth()->id(),
            'expired_at' => time() + 7200
        ]);
        $model->ticket = $model->generateTicket();
        if (!$model->save()) {
            return $this->redirectWithMessage();
        }
        $url->addData('ticket', $model->ticket);
        return $this->redirect($url);
    }

    public function logoutAction($service, $url = null) {
        $uri = new Uri();
        $serviceModel = ServiceModel::findByUrl($uri);
        if (empty($serviceModel)) {
            return;
        }
        $model = TicketModel::findByService($service);
        if (empty($model)){
            return ;
        }
        $ticket_list = TicketModel::findByUser($model->user_id);
        foreach ($ticket_list as $item) {
            $item->sendLogout();
        }
        $model->invalidTicket();
        PGTicketModel::invalidTicketByUser($model->user_id);
        return $this->redirect(empty($url) ? $uri : $url);
    }

    public function validateAction($service, $ticket) {
        $url = new Uri($service);
        $serviceModel = ServiceModel::findByUrl($url);
        if (empty($serviceModel)) {
            return $this->renderFailure('无效的 service');
        }
        $model = TicketModel::where('service', $service)
            ->where('ticket', $ticket)->one();
        if (empty($model) || $model->isExpired()) {
            return $this->renderFailure('无效的 ticket');
//            return join(PHP_EOL, [
//                'no',
//            ]);
        }
        return $this->renderData($model->user_id);
//        return join(PHP_EOL, [
//            'yes', // 'no'
//            auth()->id()
//        ]);
    }

    public function serviceValidateAction($service, $ticket) {
        $url = new Uri($service);
        $serviceModel = ServiceModel::findByUrl($url);
        if (empty($serviceModel)) {
            return;
        }
        $model = TicketModel::where('service', $service)
            ->where('ticket', $ticket)->one();
        if (empty($model)) {
            return $this->renderResponse([
                'serviceResponse' => [
                    'authenticationFailure' => [
                        'code' => 401,
                        'description' => 'ticket error',
                    ]
                ],

            ], 'xml');
        }
        return $this->renderResponse([
            'serviceResponse' => [
                'authenticationSuccess' => [
                    'user' => auth()->id()
                ]
            ]
        ], 'xml');
    }

    public function proxyAction($targetService, $pgt) {
        $model = PGTicketModel::getByTicket($pgt);
        if (empty($model)) {
            return;
        }
        $proxies = $model->proxies;
        array_unshift($proxies, $model->pgt_url);
        $ticket = new TicketModel();
        $ticket->user_id = $model->user_id;
        $ticket->service_url = $targetService;
        $ticket->proxies = $proxies;
        if (!$ticket->save()) {
            return;
        }
        return $this->renderResponse([
            'serviceResponse' => [
                'proxySuccess' => [
                    'proxyTicket' => $ticket->ticket,
                ]
            ],
//            'serviceResponse' => [
//                'proxyFailure' => [
//                    'code' => 401,
//                    'description' => 'error',
//                ]
//            ]
        ], 'xml');
    }

    public function proxyValidateAction($service, $ticket, $pgtUrl) {
        $url = new Uri($service);
        $serviceModel = ServiceModel::findByUrl($url);
        if (empty($serviceModel)) {
            return;
        }
        $ticket = TicketModel::getByTicket($ticket);
        if (empty($ticket)) {
            return;
        }
        if (!$ticket->isProxy()) {
            return;
        }
        if ($ticket->service_url != $service) {
            return;
        }
        $ticket->invalidTicket();
        $pgTicket = PGTicketModel::create([
            'user_id' => $ticket->user_id,
            'pgt_url' => $pgtUrl,
            'proxies' => $ticket->proxies,
            'ticket' => PGTicketModel::generateTicket(),
            'service_id' => $serviceModel->id
        ]);
        $iou = PGTicketModel::generateOne(64, 'PGTIOU-');
        if (!PGTicketModel::call($pgtUrl, $iou)) {
            $iou = null;
        }
        $attr = $ticket->user->getCASAttributes() || [];
        return $this->renderResponse([
            'serviceResponse' => [
                'authenticationSuccess' => [
                    'user' => $ticket->user_id,
                    'proxy' => $ticket->proxies,
                    'attributes' => $attr,
                    'proxyGrantingTicket' => $iou
                ]
            ],
//            'serviceResponse' => [
//                'authenticationFailure' => [
//                    'code' => 401,
//                    'description' => 'error',
//                ]
//            ]
        ], 'xml');
    }

    public function samlValidateAction($TARGET) {
        return $this->renderResponse([
            'localName' => 'Envelope',
            'NameIdentifier' => [
                [
                    auth()->id()
                ]
            ],
        ], 'xml');
    }
}