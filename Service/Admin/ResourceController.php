<?php
namespace Service\Admin;
/**
 * 文件管理
 */
use Infrastructure\Environment;
use Zodream\Domain\Response\Redirect;
use Zodream\Infrastructure\Request\Post;

class ResourceController extends Controller {
	function indexAction($file = null) {
		$data = Environment::getFileByDir($file);
		$this->show(array(
			'title' => $file. '-文件管理',
			'data' => $data,
			'file' => dirname($file)
		));
	}
	
	function addAction($file = null) {
		$content = null;
		if (!empty($file)) {
			$content = file_get_contents(APP_DIR.'/'.trim($file, '/\\'));
		}
		$this->show([
			'title' => empty($file) ? '新增文件' : '编辑文件'.$file,
			'content' => $content,
			'file' => $file
		]);
	}

	/**
	 * @param Post $post
	 */
	function addPost($post) {
		$file = trim($post->get('file', '/\\'));
		if (empty($file)) {
			return;
		}
		$content = $post->get('content');
		file_put_contents(APP_DIR.'/'.$file, htmlspecialchars_decode($content));
		Redirect::to('resource', 2, '保存成功！');
	}
}