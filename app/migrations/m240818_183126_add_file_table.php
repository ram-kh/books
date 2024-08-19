<?php

use yii\db\Migration;

/**
 * Class m240818_183126_add_file_table
 */
class m240818_183126_add_file_table extends Migration
{
    public $file = '{{%file}}';
    public $book = '{{%book}}';
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->file, [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'path' => $this->string(),
            'filename' => $this->string(),
            'ext' => $this->string(4),
            'size' => $this->integer(),
            'type' => $this->string(),
            'created_at' => $this->timestamp()->notNull(),
            'updated_at' => $this->timestamp()->notNull(),
            'deleted_at' => $this->timestamp()->null(),
        ]);
        $this->addColumn($this->book, 'picture_id', $this->integer());
        $this->addForeignKey(
            'fk-book-picture_id',
            $this->book,
            'picture_id',
            $this->file,
            'id',
            'CASCADE',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn($this->book, 'picture_id');
        $this->dropForeignKey('fk-book-picture_id', $this->book);
        $this->dropTable($this->file);
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m240818_183126_add_file_table cannot be reverted.\n";

        return false;
    }
    */
}
