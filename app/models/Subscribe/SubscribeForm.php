<?php

declare(strict_types=1);

namespace app\models\Subscribe;

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
    public ?int $author_id = null;
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
            ['author_id', 'integer'],
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
            'author_id' => 'Автор книги',
            'phone' => 'Телефон',
            'comment' => 'Комментарий',
            'verifyCode' => 'Проверочный код',
        ];
    }
}
