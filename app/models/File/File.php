<?php

declare(strict_types=1);

namespace app\models\File;

use app\models\ModelAR;
use Yii;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;

class File extends ModelAR
{
    public static $maxLengthExt = 10;
    public UploadedFile $file;
    public string $fileName = 'file';
    public string $uploadPath = '@runtime/uploads';
    public int $directoryLevel = 2;

    public static function tableName()
    {
        return '{{%file}}';
    }

    public function rules(): array
    {
        return [
            [
                ['filename', 'path'],
                'string',
                'max' => 255,
            ],
            [
                ['name'],
                'string',
                'max' => 64,
            ],
            [
                ['ext'],
                'string',
                'max' => self::$maxLengthExt,
            ],
            [
                ['type'],
                'string',
                'max' => 100,
            ],
            [
                ['size'],
                'integer',
            ],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'name' => 'Оригинальное название',
            'path' => 'Путь до файла',
            'filename' => 'Название файла',
            'size' => 'Размер',
            'ext' => 'Расширение',
            'type' => 'Тип',
            'created_at' => 'Дата создания',
            'updated_at' => 'Обновлен',
        ];
    }

    public function getFullPath(): string
    {
        $path = Yii::getAlias($this->uploadPath);

        if ($this->path) {
            $path .= $this->path;
        }

        $path .= DIRECTORY_SEPARATOR . $this->filename . '.' . $this->ext;

        return $path;
    }

    public function getUrl(): string
    {
        $path = '/uploads';
        if ($this->path) {
            $path .= $this->path;
        }

        $path .= '/' . $this->filename . '.' . $this->ext;

        return $path;
    }

    private function setFileName(): void
    {
        $this->filename = sha1_file($this->file->tempName);
    }

    private function setPath(): void
    {
        $this->path = '';
        if ($this->directoryLevel > 0) {
            for ($i = 0; $i < $this->directoryLevel; ++$i) {
                if (($prefix = substr($this->filename, $i + $i, 2)) !== false) {
                    $this->path .= DIRECTORY_SEPARATOR . $prefix;
                }
            }
        }
    }

    private function setFields(): void
    {
        if ($this->file instanceof UploadedFile) {
            $this->name = $this->file->name;
            $this->size = $this->file->size;
            $this->ext = $this->file->getExtension();
            $this->type = FileHelper::getMimeType($this->file->tempName);

            $this->setFileName();
            $this->setPath();
        }
    }

    public function beforeValidate(): bool
    {
        $this->setFields();

        return parent::beforeValidate();
    }

    public function beforeSave($insert): bool
    {
        if (!$this->file && parent::beforeSave($insert)) {
            return true;
        }

        if ($this->file->hasError) {
            return false;
        }

        $this->setFields();

        $fullPath = $this->fullPath;
        $success = FileHelper::createDirectory(dirname($fullPath)) && $this->file->saveAs($fullPath, false);

        if (!$success) {
            $success = copy($this->file->tempName, $fullPath);
        }

        return $success && parent::beforeSave($insert);
    }

    public function beforeDelete()
    {
        if (parent::beforeDelete()) {
            if (file_exists($this->fullPath) && !unlink($this->fullPath)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Получение объекта файла из временного файла
     *
     * @param array $name structure array ['name', 'tempName', 'type', 'size', 'error']
     * @return bool
     */
    public function getTmpFile($name): bool
    {
        $this->file = new UploadedFile($name);
        if (!$this->file) {
            return false;
        }

        return true;
    }

    /**
     * Заливка файла на сервер с сохранением его в модели
     *
     * @return bool|int - true - успешно загружено, false - ошибка загрузки, 2 - ничего не делалось,
     *     т.к. $_FILES[$attr] пустой
     */
    public function upload(): bool|int
    {
        if (!$this->file) {
            return false;
        }

        return $this->save();
    }
}