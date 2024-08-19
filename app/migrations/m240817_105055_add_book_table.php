<?php

use yii\db\Migration;

/**
 * Class m240817_105055_add_book_table
 */
class m240817_105055_add_book_table extends Migration
{
    private const AUTHOR_TABLE = 'author';
    private const BOOK_TABLE = 'book';
    private const BOOK_2_AUTHOR_TABLE = 'book_2_author';
    private string $book = '{{%' . self::BOOK_TABLE . '}}';
    private string $author = '{{%' . self::AUTHOR_TABLE . '}}';
    private string $book2author = '{{%' . self::BOOK_2_AUTHOR_TABLE . '}}';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable(
            $this->book,
            [
                'id' => $this->primaryKey(),
                'title' => $this->string()->notNull(),
                'year' => $this->integer()->notNull(),
                'isbn' => $this->string(13),
                'annotation' => $this->text(),
                'created_at' => $this->dateTime()->notNull(),
                'updated_at' => $this->dateTime()->notNull(),
                'deleted_at' => $this->dateTime(),
            ],
            $tableOptions,
        );

        $this->createTable(
            $this->book2author,
            [
                'book_id' => $this->integer()->notNull(),
                'author_id' => $this->integer()->notNull(),
            ],
        );
        $this->addPrimaryKey(self::BOOK_2_AUTHOR_TABLE . '_pk', $this->book2author, ['book_id', 'author_id']);
        $this->createIndex(
            'IX- ' . self::BOOK_2_AUTHOR_TABLE . '-book_id',
            $this->book2author,
            'book_id',
        );
        $this->createIndex(
            'IX- ' . self::BOOK_2_AUTHOR_TABLE . '-author_id',
            $this->book2author,
            'author_id',
        );
        $this->addForeignKey(
            'fk-' . self::BOOK_2_AUTHOR_TABLE . '-book_id',
            $this->book2author,
            'book_id',
            $this->book,
            'id',
            'CASCADE',
            'CASCADE',
        );
        $this->addForeignKey(
            'fk-' . self::BOOK_2_AUTHOR_TABLE . '-author_id',
            $this->book2author,
            'author_id',
            $this->author,
            'id',
            'CASCADE',
            'CASCADE',
        );

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-' . self::BOOK_2_AUTHOR_TABLE . '-book_id', $this->book2author);
        $this->dropForeignKey('fk-' . self::BOOK_2_AUTHOR_TABLE . '-author_id', $this->book2author);
        $this->dropTable($this->book2author);
        $this->dropTable($this->book);
    }
}
