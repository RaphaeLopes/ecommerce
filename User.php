<?php 

namespace Hcode\Model;

use \Hcode\DB\Sql;
use \Hcode\Model;

class Usuario extends \Hcode\Model\Model {
	
	const SESSION = "User";

	public static function login($login, $password)
	{

		$sql = new Sql();

		$results = $sql->select("SELECT * FROM tb_users WHERE deslogin = :LOGIN", array("LOGIN"=> $login));

		if(count($results) === 0)
		{
			throw new \Exception("Usuário inexistente ou senha inválida.");
		}

		$data = $results[0];

		if(password_verify($password, $data["despassword"]))
		{
			$user = new User();
			$user->setdata($data);

			$_SESSION[User::SESSION] = $user->getValues();

			return $user;
		}
		else
		{
			throw new \Exception("Usuário inexistente ou senha inválida.");
		}
	}
	
	public static function verifyLogin($inadmin = true)
	{

		if(!isset($_SESSION[User::SESSION])
			||
			!$_SESSION[User::SESSION] 
			|| 
			!(int)$_SESSION[User::SESSION]["iduser"] > 0 
			||
			(bool) $_SESSION[User::SESSION]["inadmin"] !== $inadmin) 
		{
			header("Location: /admin/login");
			exit;
		}
	}

	public static function logout()
	{
		if(!isset($_SESSION[User::SESSION])) $_SESSION[User::SESSION]  = null;
	}
	
	public static function listAll()
	{
		$sql = new Sql();

		return $sql->select("SELECT * FROM tb_users a INNER JOIN tb_persons b using(idperson) ORDER BY b.desperson");
	}

	public static function getUser($iduser)
	{
		$sql = new Sql();

		return $sql->select("SELECT * FROM tb_users a INNER JOIN tb_persons b using(idperson) ORDER BY b.desperson WHERE iduser = :IDUSER", array("IDUSER" => $iduser));
	}

	public static function Delete()
	{
		$sql = new Sql();

		$sql->select("DELETE FROM tb_users  WHERE iduser = :IDUSER", array("IDUSER" => $iduser));
	}

	public $nome = "Rasmus Lerdorf";
	protected $idade = 48;
	private $senha = "123456";

	public function verDados(){
		echo $this->nome."<br/>";
		echo $this->idade."<br/>";
		echo $this->senha."<br/>";
	}

}

 ?>