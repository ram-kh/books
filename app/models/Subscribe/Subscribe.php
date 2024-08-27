<?php

declare(strict_types=1);

namespace app\models\Subscribe;

use app\models\Author\Author;
use app\models\ModelAR;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "subscribe".
 *
 * @property int $id
 * @property string $name
 * @property int $author_id
 * @property string $email
 * @property string $phone
 * @property string $comment
 * @property string $created_at
 * @property string $updated_at
 * @property-read \yii\db\ActiveQuery $author
 * @property string|null $deleted_at
 */
class Subscribe extends ModelAR
{
    public static function tableName(): string
    {
        return 'subscribe';
    }

    public function rules(): array
    {
        return [
            [['name', 'author_id', 'email', 'phone'], 'required'],
            [['author_id'], 'integer'],
            [['comment'], 'string'],
            [['created_at', 'updated_at', 'deleted_at'], 'safe'],
            [['name', 'email', 'phone'], 'string', 'max' => 255],
        ];
    }

    public function getAuthor(): ActiveQuery
    {
        return $this->hasOne(Author::class, ['id' => 'author_id']);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getAuthorId(): int
    {
        return $this->author_id;
    }

    public function setAuthorId(int $author_id): void
    {
        $this->author_id = $author_id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): void
    {
        $this->phone = $phone;
    }

    public function getComment(): string
    {
        return $this->comment;
    }

    public function setComment(string $comment): void
    {
        $this->comment = $comment;
    }
}
