<?php

namespace App\Controllers;

use App\Core\Controller, 
	App\Models\DataBase,
	Twig_Loader_Filesystem, 
	Twig_Environment;

Class empresaController extends Controller
{
	public function __construct(){

		if ($this->isLogged() === false)
		{
			header('Location: ' . BASE_URL . '/login');
			exit;
		}
		
		if ($this->permission('menu') === false)
		{
			header('Location: ' . BASE_URL);
			exit;
		}

		if(!isset($_SESSION['message']))
		{
			$_SESSION['message'] = md5(time() . rand(0,999));
		}

    }

    public function index()
    {
		/** 
		 * Tratamento das mensagens
		 */
		if (isset($_GET['success']) && $_GET['success'] === 'true' && $_GET['t_message'] === $_SESSION['message'])
		{
			$this->data = [
				'm_bool'  => true,
				'message' => 'Operação efetuada com sucesso'
			];	
		}

		$this->data['adm'] = $this->permission('menu');
		$this->data['user'] = $_SESSION['t_grmp'][0]['name'];
		
		$db = new DataBase;
		$this->data['BASE_URL'] = BASE_URL; 

		/**
		 * Configuração de paginacao
		 */
		$limit = 7;
		
		$getTotal = $db->select('company', [ 'delet' => '' ],'COUNT(*) as c');
		
		$total = $getTotal[0]['c'];
		$this->data['paginas'] = ceil($total/$limit);
		$this->data['paginaAtual'] = 1;

		if (isset($_GET['p']) && !empty($_GET['p'])){

			$this->data['paginaAtual'] = addslashes(intval($_GET['p']));
		}

		$offset = ($this->data['paginaAtual'] * $limit) - $limit;

		for ($q=1; $q<=$this->data['paginas']; $q++)
		{
			if ($this->data['paginaAtual'] == $q) {
				$this->data['btnPag'][99999999999] = $q;
			}
			else {
				$this->data['btnPag'][] = $q;
			}
		}

		////Buscando os dados para o preenchiento da tabela
		$this->data['emp'] = $db->select('company', [ 'delet' => '' ], '*', '', $offset, $limit);
		
        echo $this->load()->render('empresa.html', $this->getData());
	}
	
	public function edit($id)
	{
		if (isset($_POST['nameEmp']) && !empty($_POST['nameEmp']))
		{
			$dados = [
				'name' 	=> addslashes($_POST['nameEmp'])
				];

			$db = new DataBase;
			$db->update('company', $dados, [ 'id' => $id[0] ]);

			header('Location: ' . BASE_URL . '/empresa?success=true&t_message='. $_SESSION['message']);
		}
	}
	public function add()
	{
		if (isset($_POST['nameEmp']) && !empty($_POST['nameEmp']))
		{
			$dados = [
				'name' 	 => addslashes($_POST['nameEmp'])
			];

			$db = new DataBase;
			$db->insert('company', $dados);

			header('Location: ' . BASE_URL . '/empresa?success=true&t_message='. $_SESSION['message']);
		}
	}

	public function delete($id)
	{
		$db = new DataBase;

		$res = $db->update('company', [ 'delet' => '*' ], [ 'id' => $id[0] ]);

		header('Location: ' . BASE_URL . '/empresa?success=true&t_message='. $_SESSION['message']);
	}

	public function __CALL($a, $b)
	{
		header('Location: ' . BASE_URL);
	}
}    
	
