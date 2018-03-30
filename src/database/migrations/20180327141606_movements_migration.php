<?php


use Phinx\Migration\AbstractMigration;

class MovementsMigration extends AbstractMigration
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
        $table = $this->table('Movements');

        $table->addColumn('historic', 'string', ['limit' => 160])
              ->addColumn('company_id', 'integer')
              ->addColumn('service_id', 'integer')
              ->addColumn('status_id', 'integer', ['default' => 0])
              ->addColumn('provider', 'string', ['limit' => 100])
              ->addColumn('analise_description', 'string', ['limit' => 100])
              ->addColumn('value_mov', 'decimal')
              ->addColumn('created', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
              ->addColumn('delet', 'char', ['limit' => 1, 'default' => ''])
              ->addForeignKey('company_id', 'Company', 'id')
              ->addForeignKey('service_id', 'Services', 'id')
              ->addForeignKey('status_id', 'Status', 'id')
              ->create();
    }
}
