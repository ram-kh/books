<?php

namespace app\models\Author;

use app\models\Book\Book;
use app\models\ModelAR;
use yii\db\ActiveQuery;
use yii\db\Query;

/**
 * This is the model class for table "author".
 *
 * @property int $id
 * @property string $lastname
 * @property string $name
 * @property string|null $surname
 * @property string $created_at
 * @property string $updated_at
 * @property string|null $deleted_at
 *
 * @property ActiveQuery $books
 */
class Author extends ModelAR
{
    public $rating;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'author';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['lastname', 'name'], 'required'],
            [['lastname', 'name', 'surname'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'lastname' => 'Фамилия',
            'name' => 'Имя',
            'surname' => 'Отчество',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления',
            'deleted_at' => 'Дата удаления',
        ];
    }

    /**
     * Gets query for [[Book2Authors]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getBooks(): ActiveQuery
    {
        return $this->hasMany(Book::class, ['id' => 'book_id'])
            ->viaTable('book_2_author', ['author_id' => 'id']);
    }

    public static function findAllAsArray(): array
    {
        return (new Query())
            ->from(self::tableName())
            ->select([
                'id',
                'fio' => 'CONCAT(lastname, " ", name, " ", surname)',
            ])
            ->where(['deleted_at' => null])
            ->all();
    }
}
