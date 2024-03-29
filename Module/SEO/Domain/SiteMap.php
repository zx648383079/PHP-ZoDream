<?php
namespace Module\SEO\Domain;

use Zodream\Helpers\Time;
use IteratorAggregate;
use ArrayIterator;

class SiteMap implements IteratorAggregate {

    const CHANGE_FREQUENCY_ALWAYS = 'always';
    const CHANGE_FREQUENCY_HOURLY = 'hourly';
    const CHANGE_FREQUENCY_DAILY = 'daily';
    const CHANGE_FREQUENCY_WEEKLY = 'weekly';
    const CHANGE_FREQUENCY_MONTHLY = 'monthly';
    const CHANGE_FREQUENCY_YEARLY = 'yearly';
    const CHANGE_FREQUENCY_NEVER = 'never';

    protected array $data = [];

    /**
     *
     * @param $url
     * @param string|int|null $lastModificationDate
     * @param string $changeFrequency always ，hourly ，daily ，weekly ，monthly ，yearly ，never
     * @param float $priority
     */
    public function add($url, string|int|null $lastModificationDate = null, string $changeFrequency = self::CHANGE_FREQUENCY_DAILY, float $priority = 0.5) {
        $this->data[] = compact('url', 'lastModificationDate', 'changeFrequency', 'priority');
    }

    public function toXml() {
        $data = array_map(function ($item) {
            $args = [
                'loc' => htmlspecialchars($item['url']),
            ];
            if (isset($item['priority'])) {
                $args['priority'] = sprintf('%.1f', min($item['priority'], 1));
            }
            if (isset($item['changeFrequency'])) {
                $args['changefreq'] = $item['changeFrequency'];
            }
            if (isset($item['lastModificationDate'])) {
                $args['lastmod'] = Time::format(is_numeric($item['lastModificationDate']) ? $item['lastModificationDate'] : strtotime($item['lastModificationDate']), 'Y-m-d');
            }
            return $args;
        }, $this->data);
        public_path()->addFile('sitemap.xml', $this->formatXml($data));
    }

    private function urlItem(array $data) {
        $xml = ['    <url>'];
        foreach ($data as $key => $item) {
            $xml[] = sprintf('        <%s>%s</%s>', $key, $item, $key);
        }
        $xml[] = '    </url>';
        return implode(PHP_EOL, $xml);
    }

    /**
     * @param array $data
     * @return string
     */
    private function formatXml(array $data) {
        $xml = [
            '<?xml version="1.0" encoding="UTF-8"?>',
            '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml">'
        ];
        foreach ($data as $item) {
            $xml[] = $this->urlItem($item);
        }
        $xml[] = '</urlset>';
        return implode(PHP_EOL, $xml);
    }

    public function getIterator(): \Traversable {
        return new ArrayIterator($this->data);
    }
}