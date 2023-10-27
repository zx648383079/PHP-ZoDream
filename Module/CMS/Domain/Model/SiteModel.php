<?php
namespace Module\CMS\Domain\Model;

use Domain\Model\Model;
use Module\CMS\Domain\Entities\SiteEntity;
use Module\CMS\Domain\Repositories\CMSRepository;
use Zodream\Helpers\Json;
use Zodream\Http\Uri;

/**
 * Class SiteModel
 * @package Module\CMS\Domain\Model
 * @property integer $id
 * @property string $title
 * @property string $keywords
 * @property string $description
 * @property string $logo
 * @property string $theme
 * @property string $match_rule
 * @property integer $is_default
 * @property integer $status
 * @property string $language
 * @property string $options
 * @property integer $created_at
 * @property integer $updated_at
 */
class SiteModel extends SiteEntity {

    public function getLogoAttribute() {
        $cover = $this->getAttributeSource('logo');
        if (empty($cover)) {
            $cover = '/assets/images/favicon.png';
        }
        return url()->asset($cover);
    }

    public function getOptionsAttribute() {
        $option = $this->getAttributeSource('options');
        if (empty($option)) {
            return [];
        }
        return is_array($option) ? $option : Json::decode($option);
    }

    public function setOptionsAttribute($value) {
        if (empty($value)) {
            $value = [];
        }
        if (is_array($value)) {
            $value = Json::encode($value);
        }
        $this->__attributes['options'] = $value;
    }

    public function getPreviewUrlAttribute() {
        return $this->url('');
    }

    public function url(string $path, array $data = []) {
        $uri = new Uri($this->match_rule);
        if (str_starts_with($path, './')) {
            $path = substr($path, 2);
        }
        if (!empty($path)) {
            $uri->appendPath($path);
        }
        $uri->addData($data)
            ->addData(CMSRepository::PREVIEW_KEY, 1);
        $request = request();
        if (empty($uri->getScheme())) {
            $uri->setScheme($request->scheme());
        }
        if (empty($uri->getHost())) {
            $uri->setHost($request->host());
        }
        return (string)url()->encode($uri);
    }

    public function saveOption(array $data) {
        $options = $this->options;
        $items = [];
        foreach ((array)$options as $item) {
            if (empty($item) || !is_array($item)) {
                continue;
            }
            $items[$item['code']] = $item;
        }
        foreach ($data as $item) {
            if (empty($item) || !is_array($item)) {
                continue;
            }
            if ($item['code'] === 'theme') {
                $this->theme = $item['value'];
            }
            if (isset($items[$item['code']])) {
                $items[$item['code']] = array_merge($items[$item['code']], $item);
                continue;
            }
            $items[$item['code']] = $item;
        }
        $this->options = array_values($items);
        $this->save();
    }
}