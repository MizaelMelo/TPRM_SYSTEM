<?php

namespace App\Controllers;

use App\Core\Controller, 
	App\Models\DataBase,
	Twig_Loader_Filesystem, 
	Twig_Environment;

Class usuarioController extends Controller
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
		if (isset($_GET['success']) && $_GET['t_message'] === $_SESSION['message'])
		{
			if ($_GET['success'] == 'true'){
				$message = 'Operação efetuada com sucesso';
			}
			else {
				$message = 'E-mail já existente na base de dados';
			}

			$this->data = [
				'm_bool'  => true,
				'message' => $message
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
		
		$getTotal = $db->select('users', [ 'delet' => '' ],'COUNT(*) as c');
		
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
		$this->data['emp'] = $db->select('users', [ 'delet' => '' ], '*', '', $offset, $limit);
		$this->data['company'] = $db->select('company', [ 'delet' => '' ], 'id, name');
		$this->data['grupo'] = $db->select('groups', [ 'delet' => '' ], 'id, name');
		$this->data['situation'] = [
			0 => [
				'id' => 0,
				'name' => 'Ativo',
			],
			[
				'id' => 1,
				'name' => 'Inativo'
			]
		]; 

		// $this->debbug($this->data['emp']);
		
        echo $this->load()->render('usuario.html', $this->getData());
	}
	
	public function edit($id)
	{
		if (isset($_POST['name']) && !empty($_POST['name']))
		{
			$senha = addslashes($_POST['senha']);

			$dados = [
				'name' 	 => addslashes($_POST['name']),
				'situation'  => addslashes($_POST['situacao']),
				'group_id'  => addslashes($_POST['grupo']),
				'company_id'  => addslashes($_POST['empresa']),
				'pass'		  => md5($senha)	
			];

			// Remove o campo da senha caso esteja em branco
			if (empty($senha))
			{
				array_pop($dados);
			}

			$db = new DataBase;
			$db->update('users', $dados, [ 'id' => $id[0] ]);

			header('Location: ' . BASE_URL . '/usuario?success=true&t_message='. $_SESSION['message']);
		}
	}
	public function add()
	{
		$bool = 'true';

		if (isset($_POST['name']) && !empty($_POST['name']))
		{
			$dados = [
				'name' 	 => addslashes($_POST['name']),
				'email'  => addslashes($_POST['email']),
				'situation'  => addslashes($_POST['situacao']),
				'group_id'  => addslashes($_POST['grupo']),
				'company_id'  => addslashes($_POST['empresa']),
				'pass'  	  => md5(PASS_USER)
			];

			$db = new DataBase;
			$dp = $db->insert('users', $dados);

			if ($dp === 00000)
			{
				$bool = 'false';
			}
			
			header('Location: ' . BASE_URL . '/usuario?success='.$bool.'&t_message='. $_SESSION['message']);
		}
	}

	public function delete($id)
	{
		$db = new DataBase;

		$res = $db->update('users', [ 'delet' => '*' ], [ 'id' => $id[0] ]);

		header('Location: ' . BASE_URL . '/usuario?success=true&t_message='. $_SESSION['message']);
	}

	public function __CALL($a, $b)
	{
		header('Location: ' . BASE_URL);
	}
}    
	
