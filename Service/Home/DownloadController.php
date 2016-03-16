<?php
namespace Service\Home;

use Domain\Model\PostsModel;
class DownloadController extends Controller {
	function indexAction() {
		$model = new PostsModel();
		$this->show('download', array(
				'title' => '下载中心',
				'page' => $model->findPage(4)
		));
	}
    
    
}