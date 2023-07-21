<?php
declare(strict_types=1);
namespace Module\Auth\Domain\Repositories;

use Domain\Repositories\FileRepository;
use Module\Auth\Domain\Exception\AuthException;
use Zodream\Helpers\Time;
use Zodream\Image\Captcha;
use Zodream\Image\HintCaptcha;
use Zodream\Image\ICaptcha;
use Zodream\Image\SlideCaptcha;
use Zodream\Infrastructure\Contracts\Http\Output;
use Zodream\Infrastructure\Contracts\Response\JsonResponse;

final class CaptchaRepository {

    const SESSION_KEY = 'captcha';

    public static function token(?string $captcha_token = ''): string {
        if (!empty($captcha_token)) {
            return $captcha_token;
        }
        return md5( request()->ip().Time::millisecond());
    }

    public static function generate(int $level = 0,
                                    string $captcha_token = '', string $type = '',
                                    int $width = 0, int $height = 0): Output {
        if (empty($level) && empty($captcha_token)) {
            $level = intval(session('level'));
        }
        $instance = match ($type) {
            'hint' => static::createHint($width, $height),
            'slide' => static::createSlide($width, $height),
            default => static::createCaptcha($level, $width, $height)
        };
        $value = $instance->generate();
        if (!empty($captcha_token)) {
            cache()->store(self::SESSION_KEY)->set($captcha_token, $value, 600);
        } else {
            session()->set(self::SESSION_KEY, $value);
        }
        if (!$instance->isOnlyImage() || request()->expectsJson() || request()->isJson()) {
            $data = $instance->toArray();
            $data['type'] = $type;
            return app(JsonResponse::class)->render($data);
        }
        return response()->image($instance);
    }

    public static function verify(mixed $captcha,
                                  string $captcha_token = '',
                                  string $type = '', bool $once = true): bool {
        $instance = match ($type) {
            'hint' => static::createHint(),
            'slide' => static::createSlide(),
            default => static::createCaptcha()
        };
        if (!empty($captcha_token)) {
            $store = cache()->store(self::SESSION_KEY);
            $storeKey = $captcha_token;
        } else {
            $store = session();
            $storeKey = self::SESSION_KEY;
        }
        $source = $store->get($storeKey);
        if (empty($source)) {
            throw AuthException::invalidCaptcha();
        }
        $res = $instance->verify($captcha, $source);
        if ($res === false) {
            $store->delete($storeKey);
            throw AuthException::invalidCaptcha();
        }
        if ($once) {
            $store->delete($storeKey);
        }
        return $res;
    }

    protected static function createHint(int $width = 0, int $height = 0): ICaptcha {
        $folder = public_path()->directory('assets');
        $instance = new HintCaptcha();
        $items = //['&#8364;', 0xe637, 0xe63b, 0xe8e4];//
            ['我', '就', '你', '哈'];//ImageHelper::randomInt(0xf030, 0xf108, 5);
        $instance->setConfigs([
            'width' => $width > 0 ? $width : 300,
            'height' => $height > 0 ? $height : 130,
            'fontSize' => 20,
            'fontFamily' => //app_path('data/fonts/iconfont.ttf'),//
                            FileRepository::fontFile(),
            'words' => $items,
            'count' => 3,
        ]);
        $instance->instance()->open($folder->file('images/banner.jpg'));
        return $instance;
    }

    protected static function createSlide(int $width = 0, int $height = 0): ICaptcha {
        $folder = public_path()->directory('assets/images');
        $instance = new SlideCaptcha();
        $instance->setConfigs([
            'width' => $width > 0 ? $width : 300,
            'height' => $height > 0 ? $height : 130,
        ]);
        $instance->instance()->open($folder->file('banner.jpg'));
        $instance->setShape($folder->file('favicon.png'));
        return $instance;
    }

    protected static function createCaptcha(int $level = 0,
                                    int $width = 0, int $height = 0): ICaptcha {
        $captcha = new Captcha();
        $captcha->setConfigs([
            'width' => $width > 0 ? $width : 100,
            'height' => $height > 0 ? $height : 30,
            'fontSize' => 20,
            'fontFamily' => FileRepository::fontFile(),
            'level' => max($level, 1)
        ]);
        return $captcha;
    }
}