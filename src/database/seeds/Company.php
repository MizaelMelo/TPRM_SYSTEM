<?php


use Phinx\Seed\AbstractSeed;

class Company extends AbstractSeed
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
                'id'     => 1, 
                'name'   => 'T.P.R.M. SA'
            ],
            [
                'id'     => 2,
                'name'   => 'CIA do Cabelo'
            ],
            [
                'id'     => 3,
                'name'   => 'GH Infotmática'
            ],
            [
                'id'     => 4,
                'name'   => 'ROP Transporte'
            ]    
        ];  
        
        $table = $this->table('Company');
        $table->insert($data)
              ->save();
    }
}
