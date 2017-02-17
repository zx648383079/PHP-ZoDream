<?php
namespace Service\Api;

use Zodream\Infrastructure\Http\Request;

class MessageController extends Controller {

    public function indexAction() {
        return $this->success([
            [
                'name' => 'Nasta',
                'text' => 'After you get up and running, you can place Font Awesome icons just about...',
                'create_at' => time()
            ]
        ]);
    }

    public function notificationAction() {
        return $this->success([
            [
                'name' => 'Vlad',
                'text' => 'Vlad posted a new article.',
                'create_at' => time()
            ]
        ]);
    }

    public function readAction() {
        return $this->success([]);
    }

    public function sendAction() {
        $data = Request::post('user_id,content');
        return $this->success([
            'type' => 1,
            'user_id' => $data['user_id'],
            'avatar' => 'Vlad',
            'username' => 'admin',
            'content' => $data['content'],
            'create_at' => time()
        ]);
    }

    public function unreadAction() {
        return $this->success([
            [
                'user_id' => 1,
                'username' => 'aa',
                'avatar' => 'Nick',
                'content' => '哈哈',
                'count' => '99',
                'create_at' => time() - 360,
            ],
            [
                'user_id' => 2,
                'username' => 'Andrey',
                'avatar' => 'Andrey',
                'content' => 'aaaaaaaaaaaaaaaaaaaaaaaaa',
                'count' => '2',
                'create_at' => time() - 360000,
            ]
        ]);
    }

    public function friendsAction() {
        return $this->success([
            [
                'user_id' => 1,
                'avatar' => 'Andrey',
                'username' => 'aaa',
                'sign' => '行动是治愈恐惧的良药，而犹豫、拖延将不断滋养恐惧。'
            ],
            [
                'user_id' => 2,
                'avatar' => 'Nick',
                'username' => 'admin',
                'sign' => 'NICK YOU TO YOU!'
            ]
        ]);
    }

    public function listAction() {
        return $this->success([
            [
                'user_id' => 1,
                'type' => 0,
                'username' => 'aa',
                'avatar' => 'Nick',
                'content' => '哈哈',
                'create_at' => time() - 3600
            ],
            [
                'user_id' => 2,
                'type' => 1,
                'username' => 'pp',
                'avatar' => 'Nasta',
                'content' => '你好年啊哈哈',
                'create_at' => time() - 120
            ]
        ]);
    }
}