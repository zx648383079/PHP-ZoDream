<?php
namespace Module\Proxy\Domain;


use Zodream\Spider\Support\Html;
use Exception;

class Spider {

    public function getProxyPool() {
        $maps = [
            ['https://www.xicidaili.com/nn/', 'getXiCi'],
            ['https://www.xicidaili.com/nt/', 'getXiCi'],
        ];
        $data = [];
        foreach ($maps as $item) {
            try {
                $args = $this->{$item[1]}($item[0]);
                if (!empty($args)) {
                    $data[] = $args;
                }
            } catch (Exception $ex) {

            }
        }
        if (empty($data)) {
            return [];
        }
        return array_merge(...$data);
    }

    public function getXiCi(string $url) {
        $content = file_get_contents($url);
        $html = new Html($content);
        $tr_list = $html->find('#ip_list', 0)->find('tr');
        $data = [];
        for ($i = 1; $i < count($tr_list); $i ++) {
            $tr = $tr_list[$i];
            $td_list = $tr->find('td');
            $data[] = [
                'ip' => $td_list[1]->plainText(),
                'port' => $td_list[2]->plainText(),
                'city' => $td_list[3]->plainText(),
                'http' => strtoupper($td_list[5]->plainText()),
                'type' => $td_list[4]->plainText(),
            ];
        }
        return $data;
    }
}