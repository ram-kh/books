<?php

use yii\db\Migration;

/**
 * Class m240827_131857_add_subscribe_table
 */
class m240827_131857_add_subscribe_table extends Migration
{
    private const TABLE = 'subscribe';
    private string $table = '{{%' . self::TABLE . '}}';
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

        $this->createTable($this->table, [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'author_id' => $this->integer()->notNull(),
            'email' => $this->string()->notNull(),
            'phone' => $this->string()->notNull(),
            'comment' => $this->text(),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime()->notNull(),
            'deleted_at' => $this->dateTime(),
        ]);

        $this->addForeignKey(
            'fk-' . self::TABLE . '-author_id',
            $this->table,
            'author_id',
            'author',
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
        $this->dropForeignKey('fk-' . self::TABLE . '-author_id', $this->table);
        $this->dropTable($this->table);
    }
}
