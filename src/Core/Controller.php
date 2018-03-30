<?php

namespace App\Core;

use App\Models\DataBase,
	Twig_Loader_Filesystem, 
	Twig_Environment,
	Twig_Extension_Debug;

Class Controller
{
	/**
	 * Variavel que armazenará dados controlados pelos métodos das classes filhas 
	 * que enviam informações para as views. 
	 *
	 * @var array
	 */
	protected $data = [];

	/**
	 * Variavel que carregará objeto de banco de dados deixando disponivel as classes
	 * filhas.
	 *
	 * @var object
	 */
	protected $dBase;

    public function __construct(){
		$this->dBase = new DataBase;

		return $this->dBase;
	}

    protected function getData()
    {
        return $this->data;
	}    

	public function permission($name)
	{
		$params = [];

		$u = new DataBase;
		$permissions = $u->select('groups', ['id' => $_SESSION['t_grmp'][0]['group_id']], 'params');

		$params = explode(',', $permissions[0]['params']);

		$idGroup = $u->select('permissions', ['description' => $name], 'id');

		if (isset($idGroup[0]['id']))
		{
			if(in_array($idGroup[0]['id'], $params))
			{
				return true;
				exit;
			}
			else {
				return false;
				exit;
			}
		}
		echo 'Permissão inexistente informada no parametro';
		exit;
	}

	public function getCompany()
	{
		$u = new DataBase;
		$company = $u->select('company', ['id' => $_SESSION['t_grmp'][0]['company_id']], 'id, name');

		return $company;
	}

    public function isLogged()
    {
        if(isset($_SESSION['cUser']) && !empty($_SESSION['cUser']))
        {
            return true;
        } else {
            return false;
        }
	}

	public function load()
	{
		$loader = new Twig_Loader_Filesystem('src/Views');

		$twig = new Twig_Environment($loader, array('debug' => true));

		$twig->addExtension(new Twig_Extension_Debug());

		return $twig;
	}
	
	public function debbug($data)
	{
		echo '<pre>';
		print_r($data);
		echo '<pre>';
		exit;
	}
}
