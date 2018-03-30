<?php


use Phinx\Seed\AbstractSeed;

class Groups extends AbstractSeed
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
                'id'      => 1,
                'name'   => 'Admin',
                'params' => '2,4'
            ],
            [
                'id'      => 2,
                'name'   => 'Usuarios',
                'params' => '1,3'
            ],
            [
                'id'      => 3,
                'name'   => 'Analista',
                'params' => '2'
            ]
        ];
        
        $table = $this->table('Groups');
        $table->insert($data)
              ->save();
    }
}
