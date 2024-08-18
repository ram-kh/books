<?php

declare(strict_types=1);

namespace app\models\Book;

use app\models\Author\Author;
use app\models\File\File;
use app\models\ModelAR;
use voskobovich\linker\LinkerBehavior;
use voskobovich\linker\updaters\ManyToManySmartUpdater;
use yii\db\ActiveQuery;
use yii\web\UploadedFile;

/**
 * This is the model class for table "book".
 *
 * @property int $id
 * @property string $title
 * @property int $year
 * @property string|null $isbn
 * @property int|null $picture_id
 * @property string|null $annotation
 * @property string $created_at
 * @property string $updated_at
 *
 * @property Author[] $authors
 */
class Book extends ModelAR
{
    public $picture = null;
//    public array $authors = [];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'book';
    }

    public function behaviors(): array
    {
        $behaviors = parent::behaviors();
        $behaviors['manyToMany'] = [
            'class' => LinkerBehavior::class,
            'relations' => [
                'authors' => [
                    'authors',
                    'updater' => ['class' => ManyToManySmartUpdater::class],
                ],
            ],
        ];

        return $behaviors;
    }
    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['title', 'year'], 'required'],
            [['year'], 'integer'],
//            [['created_at', 'updated_at'], 'safe'],
            [['authors'], 'safe'],
            [['title', 'annotation'], 'string', 'max' => 255],
            [['isbn'], 'string', 'max' => 13],
//            [['picture_ext'], 'string', 'max' => 4],
            [['picture'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'title' => 'Название книги',
            'year' => 'Год издания',
            'isbn' => 'ISBN',
            'picture' => 'Фото обложки',
            'annotation' => 'Краткое описание',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления',
        ];
    }

    /**
     * Gets query for [[Authors]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAuthors(): ActiveQuery
    {
        return $this->hasMany(Author::class, ['id' => 'author_id'])
            ->viaTable('book_2_author', ['book_id' => 'id']);
    }

    public function getFile()
    {
       return $this->hasOne(File::class, ['id' => 'picture_id']);
    }

}
