<?php
namespace Service\Admin;
/**
 * 随想
 */
use Zodream\Infrastructure\Mailer\Mailer;
use Zodream\Infrastructure\ObjectExpand\StringExpand;
use Zodream\Infrastructure\Request\Post;

class MailController extends Controller {
	function indexAction() {
		return $this->show(array(
			'title' => '邮件群发'
		));
	}

	/**
	 * @param Post $post
	 */
	function indexPost($post) {
		$title = $post->get('title');
		$content = $post->get('content');
		$isHtml = boolval($post->get('html'));
		$email = explode("\n", $post->get('email'));
		$success = 0;
		$failure = 0;
		$message = null;
		foreach ($email as $item) {
			list($address, $name) = StringExpand::explode($item, '|', 2);
			$mailer = new Mailer();
			$mailer->isHtml($isHtml);
			$mailer->addAddress($address, $name);
			$result = $mailer->send($title, $content);
			if ($result) {
				$success ++;
				continue;
			}
			$failure ++;
			$message = $mailer->getError();
		}
		$this->send([
			'success' => $success,
			'failure' => $failure,
			'message' => $message
		]);
	}
}