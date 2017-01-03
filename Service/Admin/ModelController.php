<?php
namespace Service\Admin;
/**
 * 数据库备份与恢复
 */
use Infrastructure\Environment;
use Zodream\Domain\Generate\GenerateModel;
use Zodream\Infrastructure\Http\Request\Post;
class ModelController extends Controller {
	function indexAction() {
		$data = Environment::getFiles(APP_DIR.'/document/');
		return $this->show(array(
			'data' => $data
		));
	}

	function recoverAction() {
		return $this->show();
	}

	/**
	 * @param Post $post
	 */
	function recoverPost($post) {
		$path = APP_DIR.'/document/'.$post->get('file', 'zodream.sql');
		$model = new GenerateModel();
		$file = fopen($path, 'r');
		if ($file === false) {
			$this->send('message', 'UNABLE TO OPEN FILE!');
			return;
		}
		$content = '';
		while ($line = fgets($file, 4096)) {
			$line = preg_replace('/--[\s\S]+/', '', $line);
			if (empty($line)) {
				continue;
			}
			$content .= $line;
			if (strpos($line, ';') !== false) {
				$model->execute($content);
				$content = '';
			}
		}
		fclose($file);
		$this->send('message', '导入完成！');
	}

	function backupAction() {
		$model = new GenerateModel();
		$data = $model->getDatabase();
		return $this->show(array(
			'data' => $data
		));
	}

	/**
	 * @param Post $post
	 */
	function backupPost($post) {
		$model = new GenerateModel();
		$model->setPrefix();
		$path = APP_DIR.'/document/'.$post->get('file', 'zodream.sql');
		$file = fopen($path, 'w');
		if ($file === false) {
			$this->send('message', 'UNABLE TO OPEN FILE!');
			return;
		}
		set_time_limit(0);
		fwrite($file, "--备份开始\r\n");
		foreach ($post->get('db') as $item) {
			fwrite($file, "--创建数据库开始\r\nCREATE SCHEMA IF NOT EXISTS `{$item}` DEFAULT CHARACTER SET utf8 ;\r\n
USE `{$item}` ;\r\n");
			foreach ($model->getTableByDatabase($item) as $value) {
				$table = $value;//$value['Name'];
				if ($post->has('drop')) {
					fwrite($file, "DROP TABLE IF EXISTS `{$table}`;\r\n");
				}
				fwrite($file,
					$model->getCreateTableSql($value));
				$count = $model->setTable($table)->count();
				for ($i = 0; $i < $count; $i += 20) {
					fwrite($file, $model->getInsert($model->findAll(array(
						'limit' => array(
							$i,
							$i + 20
						)
					)), $table));
				}
				fwrite($file, "\r\n\r\n");
			}
			fwrite($file, "--创建数据库结束\r\n\r\n\r\n");
		}
		fwrite($file, "--备份结束");
		fclose($file);
		$this->send('message', '备份成功！路径：'.$path);
	}

	function executeAction() {
		return $this->show();
	}

	/**
	 * @param Post $post
	 */
	function executePost($post) {
		$model = new GenerateModel();
		$sql = $post->get('sql');
		if (preg_match('/^\s*(show|select)/i', $sql, $match)) {
			$data = $model->getConnect()->getArray($sql);
		} else {
			$data = $model->getConnect()->update($sql);
		}
		$this->send(array(
			'data' => $data,
			'message' => $model->getError()
		));
	}
}