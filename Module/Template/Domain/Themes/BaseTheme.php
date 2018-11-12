<?php
namespace Module\Template\Domain\Themes;


use Module\Template\Domain\Pages\BasePage;

class BaseTheme {

    const TYPE_HTML = 0;
    const TYPE_WX = 1;
    const TYPE_VUE = 2;
    const TYPE_REACT = 3;
    const TYPE_ANGULAR = 4;
    const TYPE_APK = 5;
    const TYPE_IPA = 6;
    const TYPE_UWP = 7;

    /**
     * @var BasePage[]
     */
    protected $page_list = [];


    public function generate($type = self::TYPE_HTML) {
        return '';
    }
}