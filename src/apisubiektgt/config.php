<?php
namespace APISubiektGT;
use Exception;

class Config{
	
	protected $server;
	protected $dbuser;
	protected $dbpassword;
	protected $database;	
	protected $_ini_file;
	protected $api_key;
	protected $new_product_prefix = '';


	/**
	*	Construct
	*/
	public function __construct($ini_file){
		$this->_ini_file = $ini_file;
	}

	/**
	*	Try load current configuration
	*/
	public function load(){
		try{
			$ini_data = @parse_ini_file($this->_ini_file);
			if(!$ini_data){
				throw new Exception("Nie można załadować konfiguracji z pliku:{$this->_ini_file}", 1);			
			}			
			foreach($ini_data as $key=>$value){
				$this->{$key} = $value;
			}			
		}catch(Exception $e){
			Logger::getInstance()->log('api_error',$e->getMessage(),__CLASS__.'->'.__FUNCTION__,__LINE__);
			return 0;
		}		
		return 1;
	}


	public function getAPIKey(){
		return $this->api_key;
	}


	public function verifyAPIKey($api_key){
		if($this->api_key == trim($api_key)){
			return true;
		}
		return false;
	}

	/**
	*	Get server variable
	*/
	public function getServer(){
		return $this->server;
	}

	/**
	*	Get database variable
	*/
	public function getDatabase(){
		return $this->database;
	}

	/**
	*	Get db user name
	*/
	public function getDbUser(){
		return $this->dbuser;
	}

	/**
	*	Get db user password
	*/
	public function getDbUserPass(){
		return $this->dbpassword;
	}


	/**
	*	Get prefic for new product name
	*/
	public function getNewProductPrefix(){
		return $this->new_product_prefix;
	}


	public function newAPIKey(){
		$this->api_key = $this->generateAPIKey();
	}

	/**
	*	Create new api_key string
	*/
	static public function generateAPIKey() {
	    $key = '';
	    $keys = array_merge(range(0, 9), range('a', 'z'));
	
	    for ($i = 0; $i < 30; $i++) {
	        $key .= $keys[array_rand($keys)];
	    }	
	    return $key;
	}


	public function setServer($server){
		$this->server = $server;
	}


	public function setDbUser($dbuser){
		$this->dbuser = $dbuser;
	}


	public function setDbUserPass($dbpassword){
		$this->dbpassword = $dbpassword;
	}



	public function setDatabase($database){
		$this->database = $database;
	}

	public function setNewProductPrefix($new_product_prefix){
		$this->new_product_prefix = $new_product_prefix;
	}


	public function save(){
		$ini_str = '';
		foreach($this as $key => $value){
			if($key=='_ini_file'){continue;}
			$ini_str .= "{$key} = \"{$value}\"\n\r";
		}
		file_put_contents($this->_ini_file,$ini_str);
	}
		
}
?>