<?php

namespace App\Controllers;

use App\Core\Controller, 
	App\Models\dataBase,
	Twig_Loader_Filesystem, 
	Twig_Environment;

Class loginController extends Controller
{
	public function __construct()
	{
		if ($this->isLogged() === true)
		{
			header('Location: ' . BASE_URL);
			exit;
		}

		if(!isset($_SESSION['tprm_csrf_token']))
		{
			$_SESSION['tprm_csrf_token'] = md5(time() . rand(0,999));
		}
	}

	public function index() 
	{
		// Carrega o template com as informações
		echo $this->load()->render('login.html', array('BASE_URL' => BASE_URL, 'url' => BASE_URL . '/login/logged', 't_token' => $_SESSION['tprm_csrf_token']));
    }
	
	public function logged()
	{
		if (isset($_POST['email']) && !empty($_POST['email']))
		{
			$dados = [
				'email' => addslashes($_POST['email']),
				'pass'  => addslashes(md5($_POST['password'])),
				'delet' => '',
				'situation' => 0
			];

			$token = addslashes($_POST['t_token']);

			if ($_SESSION['tprm_csrf_token'] === $token)
			{
				$db = new DataBase;
				$res = $db->select('Users', $dados, 'id, name, group_id, company_id');

				if (count($res) > 0 )
				{	
					$_SESSION['t_grmp'] = $res;
					$_SESSION['cUser'] = md5($res['id']);
					header('Location: ' . BASE_URL);
					exit;
				}
				else {
					echo $this->load()->render('login.html', array('BASE_URL' => BASE_URL, 'url' => BASE_URL . '/login', 't_token' => $_SESSION['tprm_csrf_token'], 'msg' => 'Usuário e/ou senha inválido'));
				}
			}
			else {
				echo "Acesso restrito!";
				exit;
			}
		}
		else {
			header('Location: ' . BASE_URL);
		}
	}
	
    public function __CALL($a, $b)
    {
        header('Location: ' . BASE_URL . '/login');
		exit;
    }
}