<?php
namespace Service\Admin;
/**
 * 缓存
 */
use Zodream\Infrastructure\Url\Url;
use Zodream\Infrastructure\Factory;

class CacheController extends Controller {
	function indexAction() {
		return $this->show([
			'title' => '更新缓存',
			'data' => [
				[
					'name' => '首页',
					'url' => Url::to(['index.php', 'cache' => 'update'])
				],
				[
					'name' => '关于',
					'url' => Url::to(['index.php/about', 'cache' => 'update'])
				],
				[
					'name' => '随想',
					'url' => Url::to(['index.php/talk', 'cache' => 'update'])
				],
				[
					'name' => '实验室',
					'url' => Url::to(['index.php/laboratory', 'cache' => 'update'])
				],
				[
					'name' => '导航',
					'url' => Url::to(['index.php/navigation', 'cache' => 'update'])
				],
				[
					'name' => '废料回收',
					'url' => Url::to(['index.php/waste', 'cache' => 'update'])
				],
				[
					'name' => '论坛',
					'url' => Url::to(['index.php/forum', 'cache' => 'update'])
				]
			]
		]);
	}

	function clearAction() {
		return $this->show([
			'title' => '清除缓存'
		]);
	}
	
	function clearPost() {
		Factory::cache()->delete();
		return $this->ajax([
			'status' => 'success'
		]);
	}
	
}