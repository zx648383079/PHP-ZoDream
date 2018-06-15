<?php
namespace Module\LogView\Service;


use Module\LogView\Domain\Model\LogModel;
use Module\LogView\Domain\Tag;
use Zhuzhichao\IpLocationZh\Ip;

class AnalysisController extends Controller {


    public function indexAction($name, $value, $type = 'hour') {
        $data = LogModel::countByTime()->where($name, $value)->asArray()->all();
        $round = LogModel::selectRaw('MAX(`time`) as max, MIN(`time`) as min')->asArray()->one();
        $data = LogModel::getRoundLogs($data);
        return $this->show(compact('data', 'name', 'value'));
    }

    public function topAction($name) {
        $model = new LogModel();
        if (!$model->hasColumn($name)) {
            return $this->redirect('./');
        }
        $top_list = LogModel::groupBy($name)->select($name, 'COUNT(*) as count')
            ->orderBy('count', 'desc')->page();
        return $this->show(compact('top_list', 'name'));
    }

    /**
     * 根据已发现危险ip或路径 推断更多可疑ip及文件
     */
    public function inferAction() {
        list($ip_list, $path_list) = $this->getInferInfo();
        return $this->show(compact('ip_list', 'path_list'));
    }

    protected function getInferInfo() {
        $cache_key = 'log_infer_data';
        if ($data = cache($cache_key)) {
            return $data;
        }
        $ip_pool = Tag::get('c_ip');
        $path_pool = Tag::get('cs_uri_stem');
        $new_ip = [];
        $new_path = [];
        if (!empty($ip_pool)) {
            $new_path = LogModel::whereIn('c_ip', $ip_pool)->pluck('cs_uri_stem');
        }

        if (!empty($path_pool)) {
            $new_ip = LogModel::whereIn('cs_uri_stem', $path_pool)->pluck('c_ip');
        }
        $new_ip = array_merge($ip_pool, $new_ip);
        $new_path = array_merge($path_pool, $new_path);
        $new_ip = array_unique($new_ip);
        $new_path = array_unique($new_path);
        $path_list = [];
        foreach ($new_path as $item) {
            if ($item == '/') {
                continue;
            }
            $path_list[] = [
                $item,
                basename($item)
            ];
        }
        $ip_pool = [];
        foreach ($new_ip as $item) {
            $address = Ip::find($item);
            $ip_pool[] = [
                $item,
                empty($address) ? '-' : implode(' ', $address)
            ];
        }
        $data = [$ip_pool, $path_pool];
        cache()->set($cache_key, $data, 60);
        return $data;
    }
}