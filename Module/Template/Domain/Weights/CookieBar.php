<?php
declare(strict_types=1);
namespace Module\Template\Domain\Weights;

use Zodream\Service\Middleware\CSRFMiddleware;

class CookieBar extends Node implements INode {

    private function getCookieData(): array {
        $items = (array)__('cookie_setting');
        return [
            [
                'name' => __('Indispensable'),
                'description' => $items['indispensable'] ?? '',
                'required' => true,
                'items' => [
                    [
                        'name' => 'cookie_policy',
                        'time' => __('{0} months', [11]),
                        'description' => $items['cookie_policy'] ?? ''
                    ],
                    [
                        'name' => 'PHPSESSID',
                        'time' => __('{0} months', [11]),
                        'description' => $items['sessid'] ?? ''
                    ],
                    [
                        'name' => CSRFMiddleware::COOKIE_KEY,
                        'time' => __('{0} months', [11]),
                        'description' => $items['csrf'] ?? ''
                    ]
                ]
            ],
            [
                'name' => __('Functional'),
                'description' => $items['functional'] ?? '',
            ],
            [
                'name' => __('Performances'),
                'description' => $items['performances'] ?? '',
            ],
            [
                'name' => __('Analytics'),
                'description' => $items['analytics'] ?? '',
            ],
            [
                'name' => __('Advertising'),
                'description' => $items['advertising'] ?? '',
            ],
            [
                'name' => __('Others'),
                'description' => $items['others'] ?? '',
            ],
        ];
    }

    public function render(string $type = ''): mixed {
        if ($type === 'json') {
            return $this->getCookieData();
        }
        $html = $this->renderDetail($this->getCookieData());
        $tooltip = __('cookie_tooltip');
        $moreBtn = __('Cookie Settings');
        $accept = __('Save And Accept');
        $acceptAll = __('Accept All');
        $more = __('Learn more');
        $moreUrl = url('/agreement');
        return <<<HTML
<div class="dialog-cookie-bar">
    <div class="dialog-cookie-body">
        <div class="dialog-header">{$moreBtn}</div>
        <div class="dialog-body">
            {$tooltip}
            <a href="{$moreUrl}" target="_blank">{$more}</a>
            <div class="cookie-detail">
                {$html}
            </div>
        </div>
        <div class="dialog-footer">
            <div class="cookie-simple">
                <div class="btn-group">
                    <button class="btn btn-secondary more-btn">{$moreBtn}</button>
                    <button class="btn btn-primary accept-btn">{$acceptAll}</button>
                </div>
            </div>
            <div class="cookie-detail">
                <button class="btn btn-primary accept-btn">{$accept}</button>
            </div>
        </div>
    </div>
</div>
HTML;
    }

    private function renderDetail(array $items): string {
        $html = '';
        foreach ($items as $group) {
            $input = $this->renderInput($group);
            $table = $this->renderTable($group);
            $html .= <<<HTML
<div class="expand-card">
    <div class="card-header">
        <div class="item-icon">
            <i class="expand-icon-arrow"></i>
        </div>
        <span class="item-body">{$group['name']}</span>
        <div class="item-action">
            {$input}
        </div>
    </div>
    <div class="card-body">
        <p>{$group['description']}</p>
        {$table}
    </div>
</div>
HTML;
        }
        return $html;
    }

    private function renderInput(array $item): string {
        if (!empty($item['required'])) {
            return __('Always active');
        }
        return <<<HTML
<div class="check-toggle">
    <input type="checkbox" name="{$item['name']}" id="{$item['name']}">
    <label for="{$item['name']}"></label>
</div>
HTML;
    }

    private function renderTable(array $group): string {
        if (empty($group['items'])) {
            return '';
        }
        $html = '';
        foreach ($group['items'] as $item) {
            $html .= <<<HTML
<tr>
    <td>{$item['name']}</td>
    <td>{$item['time']}</td>
    <td class="left">{$item['description']}</td>
</tr>
HTML;
        }
        $dateTip = __('Date');
        $descTip = __('Description');
        return <<<HTML
<table class="table table-hover">
    <thead>
        <tr>
            <th>Cookie</th>
            <th>{$dateTip}</th>
            <th>{$descTip}</th>
        </tr>
    </thead>
    <tbody>
        {$html}
    </tbody>
</table>
HTML;
    }
}