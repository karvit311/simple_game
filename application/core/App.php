<?php
namespace Application\core;
class App
{
	private $db;
	private $router;
	public static $app;
	private $host="localhost";
	private $user="root";
	private $dbase="test";
	private $pass="";

	public function __construct()
	{
		static::$app = $this;
		$this->router = new \Application\core\Router(); 
		$this->db = new \PDO("mysql:host=".$this->host.";dbname=".$this->dbase,$this->user,$this->pass); 	
	}
	public function run()
	{
		$this->router->run();
	}
	public function set_db($db) 
	{
		$this->db = $db;
	}
	public function get_db() 
	{
		return $this->db;
	}
}
?>
