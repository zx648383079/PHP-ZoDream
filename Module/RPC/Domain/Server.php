<?php
namespace Module\RPC\Domain;

/**
 * Class Server
 * @package Module\RPC\Domain
 * @see https://github.com/subutux/json-rpc2php/blob/master/jsonRPC2Server.php
 */
class Server {

    protected $version = '2.0';

    protected $errorMessages = [
        '-32700' => 'Parse error',
        '-32600' => 'Invalid request',
        '-32601' => 'Method not found',
        '-32602' => 'Invalid parameters',
        '-32603' => 'Internal error',
        '-32604' => 'Authentication error',
        '-32000' => 'Extension not found'
    ];

    protected $request = [
        'jsonrpc' => '2.0',
        'method' => '',
        'params' => [],
        'id' => 0
    ];

    public function json($data) {
        $args = [
            'jsonrpc' => $this->version,
            'id' => isset($data['error']) ? null : $this->request['id']
        ];
        if (isset($data['error'])) {
            $args['error'] = $data['error'];
            return $args;
        }
        $args['result'] = isset($data['result']) ? $data['result'] : $data;
        return $args;
    }

    public function jsonSuccess($result) {
        return $this->json(compact('result'));
    }

    public function jsonFailure($message = '', $code = -32600) {
        if (is_numeric($message)) {
            list($code, $message) = [$message, isset($this->errorMessages[$message]) ? $this->errorMessages[$message] : 'internalError'];
        }
        $code = intval($code);
        return $this->json([
            'error' => compact('code', 'message')
        ]);
    }
}