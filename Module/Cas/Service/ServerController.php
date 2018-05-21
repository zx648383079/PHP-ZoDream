<?php
namespace Module\Cas\Service;

use Module\Cas\Domain\Model\TicketModel;
use Zodream\Domain\Access\Auth;
use Zodream\Http\Uri;

class ServerController extends Controller {

    protected function isValidUri($url) {
        return true;
    }

    public function loginAction($service) {
        if (!$this->isValidUri($service)) {
            return $this->redirectWithMessage();
        }
        if (Auth::guest()) {
            // 登录
            return $this->redirectWithAuth();
        }
        TicketModel::where('service', $service)->delete();
        $model = TicketModel::create([
            'service' => $service,
            'ticket' => TicketModel::generateTicket(),
            'user_id' => Auth::id(),
            'expired_at' => time() + 36000
        ]);
        $url = new Uri($service);
        $url->addData('ticket', $model->ticket);
        return $this->redirect($url);
    }

    public function logoutAction($service, $url) {
        if (!$this->isValidUri($service)) {
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
        $model->delete();
        return $this->redirect($url);
    }

    public function validateAction($service, $ticket) {
        if (!$this->isValidUri($service)) {
            return $this->jsonFailure('无效的 service');
        }
        $model = TicketModel::where('service', $service)
            ->where('ticket', $ticket)->one();
        if (empty($model)) {
            return $this->jsonFailure('无效的 ticket');
//            return join(PHP_EOL, [
//                'no',
//            ]);
        }
        return $this->jsonSuccess($model->user_id);
//        return join(PHP_EOL, [
//            'yes', // 'no'
//            Auth::id()
//        ]);
    }

    public function serviceValidateAction($service, $ticket) {
        if (!$this->isValidUri($service)) {
            return;
        }
        $model = TicketModel::where('service', $service)
            ->where('ticket', $ticket)->one();
        if (empty($model)) {
            return $this->json([
                'localName' => 'serviceResponse',
                'authenticationFailure' => [
                    [
                        '@attributes' => [
                            'code' => 401,
                        ],
                        '@value' => 'ticket error',
                    ]
                ]
            ], 'xml');
        }
        return $this->json([
            'localName' => 'serviceResponse',
            'authenticationSuccess' => [
                [
                    'user' => [
                        Auth::id()
                    ]
                ]
            ],
        ], 'xml');
    }

    public function proxyAction($targetService, $pgt) {
        return $this->json([
            'localName' => 'serviceResponse',
            'proxySuccess' => [
                [
                    'proxyTicket' => [
                        Auth::id()
                    ],
                ]
            ],
//            'proxyFailure' => [
//                [
//                    '@attributes' => [
//                        'code' => 401,
//                    ],
//                    '@value' => 'error',
//                ]
//            ]
        ], 'xml');
    }

    public function proxyValidateAction($service, $ticket, $pgtUrl) {
        if (!$this->isValidUri($service)) {
            return;
        }
        return $this->json([
            'localName' => 'serviceResponse',
            'authenticationSuccess' => [
                [
                    'user' => [
                        Auth::id()
                    ],
                    'proxy' => [
                        ''
                    ]
                ]
            ],
//            'authenticationFailure' => [
//                [
//                    '@attributes' => [
//                        'code' => 401,
//                    ],
//                    '@value' => 'error',
//                ]
//            ]
        ], 'xml');
    }

    public function samlValidateAction($TARGET) {
        return $this->json([
            'localName' => 'Envelope',
            'NameIdentifier' => [
                [
                    Auth::id()
                ]
            ],
        ], 'xml');
    }
}