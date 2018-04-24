<?php namespace app\server\controllers;

    //Necessário devido a utilização do autoload
	use PDO;
	
	//COMO UTILIZAR: Conn::getConn()->query("SUA QUERY AQUI DENTRO");
	
	class Conn
	{
        private static $host     = "localhost";
		private static $user     = "root";
		private static $pass     = "";
		private static $dbName   = "anjosderua";
		private static $Connect  = null; 
		
		private static function Conectar()
		{
			try
			{
				if(self::$Connect == null)
				{
					self::$Connect = new PDO("mysql:host=".self::$host.";dbname=".self::$dbName."", self::$user, self::$pass);
				}
			}catch(PDOException $e){
				PHPErro($e->getCode(), $e->getMessage(), $e->getFile(), $e->getLine() );
				die;
			}
			
			self::$Connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			return self::$Connect;
		}
		
		public static function getConn()
		{
			return self::Conectar();
		}
	}