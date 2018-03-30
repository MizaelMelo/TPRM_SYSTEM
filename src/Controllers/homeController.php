<?php

namespace App\Controllers;

use App\Core\Controller, 
	App\Models\DataBase,
	Twig_Loader_Filesystem, 
	Twig_Environment;

Class homeController extends Controller
{
	public function __construct(){

		if ($this->isLogged() === false)
		{
			header('Location: ' . BASE_URL . '/login');
			exit;
		}
		
		if(!isset($_SESSION['message']))
		{
			$_SESSION['message'] = md5(time() . rand(0,999));
		}
	}
	
	public function index()
	{
		$mbool = false;
		$message = '';
		/** 
		 * Tratamento das mensagens
		 */
		if (isset($_GET['success']) && $_GET['success'] === 'true' && $_GET['t_message'] === $_SESSION['message'])
		{
			$mbool = true;
			$message = 'Operação efetuada com sucesso';
		}

		$btnIncluir = $this->permission('incluir_transacao');
		$adm 		= $this->permission('menu');

		$db = new DataBase;
		$services = $db->select('services', [ 'situation' => 0, 'delet' => '' ], 'id, description, value_service');
		
		$this->data = array(
							'BASE_URL' 		=> BASE_URL, 
							'btn_incluir' 	=> $btnIncluir, 
							'user' 			=> $_SESSION['t_grmp'][0]['name'],
							'company' 		=> $this->getCompany()[0]['name'],
							'services'		=> $services,
							'adm'			=> $adm,
							'm_bool'		=> $mbool,
							'message'		=> $message
						);

		echo $this->load()->render('home.html', $this->getData());
	}

	public function getMovements()
	{
		$view = $this->permission('visualizar_transacao');
		$excluir = $this->permission('excluir_transacao');
		
		if($view == false)
		{
			$where = [ 'company_id' => $this->getCompany()[0]['id'],'delet' => '' ];
		}
		else {
			$where = [ 'delet' => '' ];
		}

		$db = new DataBase;
		$mov['dados'] 		= $db->select('movements', $where, 'id, historic, analise_description, status_id, service_id, company_id, provider, value_mov, created');
		$mov['tpservice'] 	= $db->select('services', [ 'delet' => '' ], 'id, description, value_service');
		$mov['company'] 	= $db->select('company', [ 'delet' => '' ], 'id, name');
		$mov['status'] 	= $db->select('status');
		$mov['view'] 	= $view;
		$mov['excluir'] = $excluir;
		
		echo json_encode($mov);
		exit;
	}

	public function add()
	{
		if (isset($_POST['contratada']) && !empty($_POST['contratada']))
		{
			$dados = [
				'provider' 	 => addslashes($_POST['contratada']),
				'service_id' => addslashes($_POST['tpServico']),
				'historic'   => addslashes($_POST['descricao']),
				'value_mov'   	 => addslashes($_POST['value']),
				'company_id' => $this->getCompany()[0]['id'],
				'status_id'  => 1
			];

			$db = new DataBase;
			$db->insert('movements', $dados);

			header('Location: ' . BASE_URL . '?success=true&t_message='. $_SESSION['message']);
		}
	}

	public function delete()
	{
		$id = addslashes($_POST['q']);
		
		$db = new DataBase;

		$res = $db->update('movements', [ 'delet' => '*' ], [ 'id' => $id, 'status_id' => '1' ]);

		echo json_encode($res);
	}

	public function approve()
	{
		if (isset($_POST['contratante']) && !empty($_POST['contratante']))
		{	
			$id = addslashes($_POST['moviments']);
			$company_id = addslashes($_POST['company_id']);

			$dados = [
				'analise_description' => addslashes($_POST['descricao_analise']),
				'status_id'  => 2
			];

			$db = new DataBase;
			$db->update('movements', $dados, ['id' => $id]);
			
			$company = $db->select('company', [ 'id' => $company_id ], 'id, saldo')[0];
			
			$db->update('company', [ 'saldo' => 5+$company['saldo'] ], [ 'id' => $company['id'] ]);

			header('Location: ' . BASE_URL . '?success=true&t_message='. $_SESSION['message']);
		}
	}

	public function reprove()
	{
		if (isset($_POST['contratante']) && !empty($_POST['contratante']))
		{
			$company_id = addslashes($_POST['company_id']);

			$dados = [
				'analise_description' => addslashes($_POST['descricao_analise']),
				'status_id'  => 3
			];

			$db = new DataBase;
			$db->update('movements', $dados, ['id' => addslashes($_POST['moviments'])]);
			
			$company = $db->select('company', [ 'id' => $company_id ], 'id, saldo')[0];
			
			$db->update('company', [ 'saldo' => 5+$company['saldo'] ], [ 'id' => $company['id'] ]);

			header('Location: ' . BASE_URL . '?success=true&t_message='. $_SESSION['message']);
		}
	}

	public function logout()
	{
		unset($_SESSION['cUser']);
		header('Location: ' . BASE_URL);
		exit;
	}
}