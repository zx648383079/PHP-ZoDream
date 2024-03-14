<?php
declare(strict_types=1);
namespace Domain\Middlewares;

use Zodream\Http\Uri;
use Zodream\Infrastructure\Contracts\Application;
use Zodream\Infrastructure\Contracts\Config\Repository;
use Zodream\Infrastructure\Contracts\Translator;
use Zodream\Infrastructure\I18n\I18n;
use Zodream\Route\Rewrite\RewriteEncoder;

final class UrlRouterMiddleware extends RewriteEncoder {

    const EnabledModuleItems = ['blog', 'auth'];
    private static bool $enabled = true;

    public static function enable(bool $enable = true): void {
        self::$enabled = $enable;
    }

    public function __construct(Repository $config,
                                protected Application $app,
                                protected Translator $translator) {
        parent::__construct($config);
    }

    public function encode(Uri $url, callable $next): Uri {
        /** @var Uri $url */
        $url = $next($url);
        $path = trim($url->getPath(), '/');
        $enable = $this->isEnableModule($path);
        $params = $url->getData();
        list($path, $params) = $this->enRewrite($path, $params);
        if ($enable) {
            $path = $this->encodeLocale($path);
        }
        return $url->setPath($path)->setData($params);
    }

    public function decode(Uri $url, callable $next): Uri {
        list($path, $params) = $this->deRewrite($url->getPath());
        return $next($url->setPath($this->decodeLocale($path))->addData($params));
    }

    protected function decodeLocale(string $path): string {
        if (empty($path)) {
            return $path;
        }
        $i = strpos($path, '/');
        $locale = $i === false ? $path : substr($path, 0, $i);
        if (!$this->translator->isLocale($locale)) {
            return $path;
        }
        $this->app->setLocale($locale);
        return $i === false ?  '' : substr($path, $i + 1);
    }

    protected function encodeLocale(string $path): string {
        if (!self::$enabled) {
            return $path;
        }
        $locale = $this->app->getLocale();
        if (empty($locale) || $locale === I18n::DEFAULT_LANGUAGE) {
            return $path;
        }
        if ($path === '') {
            return $locale;
        }
        return sprintf('%s/%s', $locale, trim($path, '/'));
    }

    protected function isEnableModule(string $path): bool {
        if ($path === '') {
            return true;
        }
        $i = strpos($path, '/');
        $locale = $i === false ? $path : substr($path, 0, $i);
        return !$this->translator->isLocale($locale) && in_array($locale, self::EnabledModuleItems);
    }
    
}