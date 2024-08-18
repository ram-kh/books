<?php

declare(strict_types=1);

namespace app\models\User;

use Yii;
use yii\base\Model;

/**
 * SubscribeForm is the model behind the subscribe form.
 */
class SubscribeForm extends Model
{
    public string $name = '';
    public string $email = '';
    public string $phone = '';
    public string $comment = '';
    public ?int $authorId = null;
    public string $verifyCode = '';


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            // name, email, subject and body are required
            [['name', 'email', 'phone'], 'required'],
            // email has to be a valid email address
            ['email', 'email'],
            ['comment', 'string'],
            ['authorId', 'integer'],
            ['phone', 'match', 'pattern' => '/^((8|\+7)[\- ]?)?(\(?\d{3}\)?[\- ]?)?[\d\- ]{7,10}$/'],
            // verifyCode needs to be entered correctly
            ['verifyCode', 'captcha'],
        ];
    }

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
            'name' => 'Ваше имя',
            'year' => 'Год издания',
            'authorId' => 'Автор книги',
            'phone' => 'Телефон',
            'comment' => 'Комментарий',
            'verifyCode' => 'Проверочный код',
        ];
    }

    /**
     * Sends an email to the specified email address using the information collected by this model.
     * @param string $email the target email address
     * @return bool whether the model passes validation
     */
    public function subscribe(string $email): bool
    {
        if (!$this->validate()) {
            return false;
        }

        Yii::$app->mailer->compose()
            ->setTo($email)
            ->setFrom([Yii::$app->params['senderEmail'] => Yii::$app->params['senderName']])
            ->setReplyTo([$this->email => $this->name])
            ->setSubject('Подписка на рассылку')
            ->setTextBody(
                'Вы подписались на рассылку на адрес: ' . $this->email . ' и телефон: ' . $this->phone . ' и комментариями: ' . $this->comment,
            )
            ->send();

        return true;
    }
}
