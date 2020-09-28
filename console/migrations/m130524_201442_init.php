<?php

use yii\db\Migration;

class m130524_201442_init extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%book}}', [
            'id' => $this->primaryKey(),
            'author_id' => $this->integer()->notNull(),
            'name' => $this->string()->notNull(),
            'rating' => $this->float(2)->null(),
            'photo_url' => $this->string()->null(),
        ], $tableOptions);
        $this->createIndex('name_author_id', '{{%book}}', ['name', 'author_id'], true);
        $this->createIndex('author_id', '{{%book}}', 'author_id');

        $this->createTable('{{%author}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull()->unique(),
        ], $tableOptions);

        $this->addForeignKey("fk_book_author", "{{%book}}", "author_id", "{{%author}}", "id", "CASCADE", "CASCADE");
    }

    public function down()
    {
        $this->dropForeignKey('fk_book_author', '{{%book}}');

        $this->dropTable('{{%author}}');
        $this->dropTable('{{%book}}');
    }
}
