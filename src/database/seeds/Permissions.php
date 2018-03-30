<?php


use Phinx\Seed\AbstractSeed;

class Permissions extends AbstractSeed
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
                'id' => 1,
                'description'   => 'incluir_transacao'
            ],
            [   
                'id' => 2,
                'description'   => 'visualizar_transacao'
            ],
            [   
                'id' => 3,
                'description'   => 'excluir_transacao'
            ],
            [   
                'id' => 4,
                'description'   => 'menu'
            ]
        ];    
        
        $table = $this->table('Permissions');
        $table->insert($data)
              ->save();
    }
}
