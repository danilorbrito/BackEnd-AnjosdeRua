<?php namespace app\server\controllers;

	class Upload
	{
		private static $path_temp;
		private static $nome;
		private static $destino;
		private static $size;
		private static $extensao;
		private static $tamanho;
		private static $permitidos;
		private static $tamanhoMAXIMO = 10240; //verificar essa conf no php.ini upload_max_filesize
		private static $logError;
		
		public static function move($_destino, $_permitidos=array(".jpg",".jpeg",".png"))
		{
			self::$path_temp   = $_FILES["arquivo"]["tmp_name"];
			self::$nome        = $_FILES["arquivo"]["name"];
			self::$destino     = $_destino;
			self::$size        = $_FILES["arquivo"]["size"];
			self::$permitidos  = $_permitidos;
			self::$tamanho     = round(self::$size / self::$tamanhoMAXIMO);
			self::createName();
			return self::save();
		}
		
		
		private static function createName()
		{
			self::$extensao  = strrchr(self::$nome, '.');
			self::$extensao  = strtolower(self::$extensao);
			self::$nome      = md5(microtime()).self::$extensao;
		}

		public static function save()
		{
			if( self::validateFormat() == false )
			{
				self::$logError = "Formato inválido";
				return false;
			}
			elseif( self::validateSize() == false )
			{
				self::$logError = "Tamanho máximo 10M";
				return false;
			}
			else
			{	
				return copy(self::$path_temp,self::$destino."/".self::$nome);
			}
		}

		private static function validateFormat()
		{
			if (in_array(self::$extensao, self::$permitidos)){
				return true;
			}else{
				return false;
			}
		}

		private static function validateSize()
		{
			if( self::$tamanho < self::$tamanhoMAXIMO){
				return true;
			}else{
				return false;
			}
		}
		
		public static function getError()
		{
			return self::$logError;
		}
		
		public static function getName()
		{
			return self::$nome;
		}
	}