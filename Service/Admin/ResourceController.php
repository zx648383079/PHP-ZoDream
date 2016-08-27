<?php
namespace Service\Admin;
/**
 * 文件管理
 */
use Infrastructure\Environment;
use Zodream\Domain\Response\Redirect;
use Zodream\Infrastructure\Factory;
use Zodream\Infrastructure\Request;
use Zodream\Infrastructure\Request\Post;

class ResourceController extends Controller {
	function indexAction($file = null) {
		$file = Factory::root()->childDirectory($file);
		return $this->show(array(
			'title' => $file->getName(). '-文件管理',
			'file' => $file
		));
	}
	
	function addAction($file = null) {
        $content = Request::post('content');
	    if (Request::isPost() &&
            Factory::root()
                ->childFile(Request::post('file'))
                ->write(htmlspecialchars_decode($content))) {
	        return $this->redirect('resource', 2, '保存成功！');
        }
		if (!empty($file) && Factory::root()->hasFile($file)) {
			$content = Factory::root()->childFile($file)->read();
		}
		return $this->show([
			'title' => empty($file) ? '新增文件' : '编辑文件'.$file,
			'content' => $content,
			'file' => $file
		]);
	}
}