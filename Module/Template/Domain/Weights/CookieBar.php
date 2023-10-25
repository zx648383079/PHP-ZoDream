<?php
declare(strict_types=1);
namespace Module\Template\Domain\Weights;

class CookieBar extends Node implements INode {

    private function getCookieData(): array {
        return [
            [
                'name' => __('Indispensable'),
                'description' => 'Essential Cookies are absolutely necessary for the proper functioning of a website. These cookies are anonymous to ensure the basic functionality and security features of the website.',
                'required' => true,
                'items' => [
                    [
                        'name' => 'cookie_policy',
                        'time' => '11 months',
                        'description' => 'Cookies are set by the Cookie Plugin to store the user\'s consent to the use of cookies; it does not store any personal data.'
                    ]
                ]
            ],
            [
                'name' => __('Functional'),
                'description' => 'Functionality Cookies help perform certain functions, such as sharing website content on social media platforms, collecting feedback and other third-party functions.',
            ],
            [
                'name' => __('Performances'),
                'description' => 'Performance cookies are used to understand and analyze key performance indicators of a website, which helps provide a better user experience for visitors.',
            ],
            [
                'name' => __('Analytics'),
                'description' => 'Analytics cookies are used to understand how visitors interact with a website. These cookies help provide information on metrics such as the number of visitors, bounce rates, traffic sources, and more.',
            ],
            [
                'name' => __('Advertising'),
                'description' => 'Advertising cookies are used to provide visitors with relevant advertising and marketing campaigns. These cookies track visitors across websites and collect information to deliver customized advertisements.',
            ],
            [
                'name' => __('Others'),
                'description' => 'Other unclassified cookies are those that are being analyzed but not yet classified.',
            ],
        ];
    }

    public function render(string $type = ''): mixed {
        if ($type === 'json') {
            return $this->getCookieData();
        }
        $html = $this->renderDetail($this->getCookieData());
        return <<<HTML

<div class="dialog-cookie-bar">
    <div class="dialog-cookie-mask"></div>
    <div class="dialog-cookie-body">
        <div class="dialog-header">Cookie Settings</div>
        <div class="dialog-body">
            Our website uses some cookies and records your IP address for the purposes of accessibility, security, and managing your access to the telecommunication network. You can disable data collection and cookies by changing your browser settings, but it may affect how this website functions.
            <a href="">Learn more</a>
            <div class="cookie-detail">
                {$html}
            </div>
        </div>
        <div class="dialog-footer">
            <div class="cookie-simple">
                <div class="btn-group">
                    <button class="btn btn-secondary more-btn">Cookie Settings</button>
                    <button class="btn btn-primary accept-btn">Accept All</button>
                </div>
            </div>
            <div class="cookie-detail">
                <button class="btn btn-primary accept-btn">Save And Accept</button>
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
            return 'Always active';
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
    <td>{$item['description']}</td>
</tr>
HTML;
        }
        return <<<HTML
<table class="table table-hover">
    <thead>
        <tr>
            <th>Cookie</th>
            <th>Date</th>
            <th>Description</th>
        </tr>
    </thead>
    <tbody>
        {$html}
    </tbody>
</table>
HTML;
    }
}