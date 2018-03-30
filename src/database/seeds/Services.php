<?php


use Phinx\Seed\AbstractSeed;

class Services extends AbstractSeed
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
                'description'       => 'Limpeza de pele',
                'value_service'     => '40'
            ],[
                'description'       => 'Informática (manutenção)',
                'value_service'     => '35'
            ],[
                'description'       => 'Guia de mudança',
                'value_service'     => '100'
            ],[
                'description'       => 'Chef particular',
                'value_service'     => '150'
            ]
        ];    
        
        $table = $this->table('Services');
        $table->insert($data)
              ->save();
    }
}
