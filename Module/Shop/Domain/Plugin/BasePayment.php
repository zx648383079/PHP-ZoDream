<?php
namespace Module\Shop\Domain\Plugin;

use Zodream\Infrastructure\Support\Html;

abstract class BasePayment {

    const STATUS_SUCCESS = 'SUCCESS';
    const STATUS_FAILURE = 'FAILURE';

    /**
     * @var array
     */
    protected $configs;

    abstract public function getName(): string;

    public function getIntro(): string {
        return $this->getName();
    }

    public function settings(): array {
        return [];
    }

    abstract public function preview(): string;

    abstract public function pay(array $log): array;

    abstract public function callback(array $input): array;

    abstract public function refund(array $log): array;

    /**
     * @param array $configs
     * @return $this
     */
    public function setConfigs(array $configs) {
        $this->configs = $configs;
        return $this;
    }

    public function getConf($name): string {
        return isset($this->configs[$name]) ? $this->configs[$name] : '';
    }

    public function toForm(string $action, array $data, string $method = 'POST') {
        $items = [];
        foreach ($data as $name => $value) {
            $items[] = Html::input('hidden', compact('name', 'value'));
        }
        $id = 'pay-form';
        $form = Html::form(implode('', $items), compact('action', 'method', 'id'));
        return compact('form');
    }

    public function toHtml(string $html) {
        return compact('html');
    }

    public function toUrl($url, array $data = []) {
        if (!empty($data)) {
            $url = url($url, $data);
        }
        return compact('url');
    }

    public function toConfirm(array $confirm) {
        return compact('confirm');
    }

}
