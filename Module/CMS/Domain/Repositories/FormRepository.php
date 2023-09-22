<?php
declare(strict_types=1);
namespace Module\CMS\Domain\Repositories;

use Exception;
use Module\Auth\Domain\Repositories\AuthRepository;
use Module\Auth\Domain\Repositories\CaptchaRepository;
use Module\CMS\Domain\Fields\BaseField;
use Module\CMS\Domain\FuncHelper;
use Module\CMS\Domain\Model\ModelModel;
use Zodream\Infrastructure\Contracts\Http\Input;
use Zodream\Infrastructure\Mailer\Mailer;
use Zodream\Validate\Validator;

class FormRepository {

    /**
     * @return ModelModel|array
     * @throws \Exception
     */
    public static function getModel(Input $input) {
        if ($input->has('id')) {
            return FuncHelper::model(intval($input->get('id')));
        }
        return FuncHelper::model($input->get('model'));
    }

    public static function formInputList(Input $input): array {
        $model = static::getModel($input);
        $scene = CMSRepository::scene()->setModel($model);
        $field_list = $scene->fieldList();
        $inputItems = [];
        foreach ($field_list as $item) {
            $inputItems[] = $scene->toInput($item, [], true);
        }
        return $inputItems;
    }

    public static function save(Input $input) {
        $model = static::getModel($input);
        if (empty($model) || $model['type'] != 1) {
            throw new Exception('表单数据错误');
        }
        $scene = CMSRepository::scene()->setModel($model);
        $id = 0;
        $captcha = $input->string('captcha');
        if (!empty($captcha)) {
            if (!CaptchaRepository::verify($captcha)) {
                throw new Exception('验证码错误');
            }
        } elseif (BaseField::fieldSetting($model, 'open_captcha')) {
            throw new Exception('请输入验证码');
        }
        $data = $scene->validate($input->get());
        if (BaseField::fieldSetting($model, 'is_extend_auth')) {
            // 注册
            AuthRepository::register(
                $input->string('name'),
                $input->string('email'),
                $input->string('password'),
                $input->string('confirm_password'),
                true, '', [
                    'sex' => $input->int('sex'),
                ]
            );
        }
        if (BaseField::fieldSetting($model, 'is_only')) {
            if (auth()->guest()) {
                throw new Exception('请先登录！');
            }
            $id = $scene->query()
                ->where('model_id', $model['id'])
                ->where('user_id', auth()->id())
                ->value('id');
        }
        if ($id > 0) {
            $res = $scene->update($id, $data);
        } else {
            $res = $scene->insert($data);
            $id = intval($res);
        }
        if (!$res) {
            throw new Exception('表单填写有误');
        }
        $notifyMail = BaseField::fieldSetting($model, 'notify_mail');
        if (!empty($notifyMail) && Validator::email()->validate($notifyMail)) {
            // TODO 发送邮件通知对方
            $content = FuncHelper::formRender($model, $id);
            if (!empty($content)) {
                try {
                    $mail = new Mailer();
                    $res = $mail->isHtml()
                        ->addAddress($notifyMail)
                        ->send(sprintf('CMS新表单【%s】', $model['name']), $content);
                } catch (Exception $ex) {
                    logger()->error($ex->getMessage());
                }
            }
        }
        return $res;
    }

}