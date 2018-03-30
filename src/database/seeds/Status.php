<?php


use Phinx\Seed\AbstractSeed;

class Status extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     */
    public function run()
    {
        $data = [
            [
                'id'          => 1,
                'description' => 'Pendente'
            ],[
                'id'          => 2,
                'description' => 'Aprovado'
            ],[
                'id'          => 3,
                'description' => 'Reprovado'
            ]
        ];    
        
        $table = $this->table('Status');
        $table->insert($data)
              ->save();
    }
}
