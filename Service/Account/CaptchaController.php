<?php
declare(strict_types=1);
namespace Service\Account;
/**
 * 验证码
 */
use Zodream\Image\Captcha;
use Zodream\Image\SlideCaptcha;

use Zodream\Infrastructure\Contracts\Http\Input as Request;
use Zodream\Infrastructure\Contracts\Http\Output;


class CaptchaController extends Controller {
	public function rules() {
		return array(
			'*' => '*'
		);
	}

    public function indexAction(Request $request, Output $output) {
		$level = intval($request->get('level'));
		if (empty($level)) {
			$level = session('level');
		}
		$captcha = new Captcha();
		$captcha->setConfigs([
            'width' => intval($request->get('width', 100)),
            'height' => intval($request->get('height', 30)),
            'fontSize' => 20,
            'fontFamily' => (string)app_path()->file('data/fonts/YaHei.ttf')
        ]);
		return $output->image($captcha->generate($level));
	}

    public function slideCheckAction() {
	    $x = floor(request()->get('x'));
	    $c = session('slider_x');
	    if (abs($x - $c) < 5) {
	        return $this->renderData(true);
        }
        return $this->renderFailure($c);
    }

    public function slideAction() {
        $img = new SlideCaptcha();
        $img->instance()->open(public_path('assets/images/banner.jpg'));
        $img->instance()->scale(300, 130);
        $img->setShape('E:\Desktop\1.jpg');
        $img->generate();

        $args = range(0, 7);
        shuffle($args);
        list($bg, $points, $size) = $img->sortBy($args);
        $html = '';
        foreach ($points as $point) {
            $html .= sprintf('<div class="slide-img" style="background-position: %spx %spx"></div>', $point[0], $point[1]);
        }
        $width = $img->instance()->getWidth();
        $height = $img->instance()->getHeight();
        $bg_data = $bg->toBase64();
        session()->set('slider_x', $img->getPoint()[0]);
        $html = <<<HTML
<style>
.slide-box {
    width: {$width}px;
    height: {$height}px;
    position: relative;
}
.slide-box .slide-img {
    float: left;
    margin: 0;
    padding: 0;
    background-image: url({$bg_data});
    background-repeat: no-repeat;
    width: {$size[0]}px;
    height: {$size[1]}px;
}
.slide-box .slide-cut {
    position: absolute;
    top: {$img->getPoint()[1]}px;
    background-image: url({$img->getSlideImage()->toBase64()});
    background-repeat: no-repeat;
    width: {$img->getSlideImage()->getWidth()}px;
    height: {$img->getSlideImage()->getHeight()}px;
    z-index: 9;
}
</style>
<div class="slide-box">
    <div class="slide-cut"></div>
    <div class="slide-list">
        {$html}
    </div>
    
</div>
HTML;
        return $this->showContent($html);
    }
}