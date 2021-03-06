<?php


use Phinx\Migration\AbstractMigration;

class ServicesMigration extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change()
    {
        $table = $this->table('Services');

        $table->addColumn('description', 'string', ['limit' => 200])
              ->addColumn('situation', 'integer', ['limit' => 1, 'default' => 0])
              ->addColumn('value_service', 'decimal')
              ->addColumn('created', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
              ->addColumn('delet', 'char', ['limit' => 1, 'default' => ''])
              ->create();
    }
}
