<?php
namespace APISubiektGT\SubiektGT;

use APISubiektGT\SubiektGT as SubiektGT;

abstract class SubiektObj{

	protected $subiektGt = false;
	protected $is_exists = false;
	protected $gt_id = false;
	protected $exclude_attibs = array('subiektGt',
							'exclude_attibs',
							'is_exists');	

	public function __construct($subiektGt, $objDetail = array()){
		foreach($objDetail as $key=>$value){
			if(!is_array($value) && is_string($value)){
				$this->{$key} = mb_convert_encoding($value,'ISO-8859-2');
			}else{
				$this->{$key} = $value;	
			}
		}
		$this->subiektGt = $subiektGt;
	}
	

	protected function excludeAttr($name){
		if(is_array($name)){
			$this->exclude_attibs = array_merge($this->exclude_attibs,$name);
		}else{
			$this->exclude_attibs = array_merge($this->exclude_attibs,array($name));
		}
	}

	abstract protected function setGtObject();
	abstract protected function getGtObject();	
	abstract public function add();
	abstract public function update();
	abstract public function getGt();

	public function isExists(){
		return $this->is_exists;
	}


	public function get(){
		$ret_data = array();
		foreach ($this as $key => $value) {
			if(in_array($key,$this->exclude_attibs)){
				continue;
			}
			$ret_data[$key] = self::toUtf8($value);
		}	
		return $ret_data;
	}

	protected function toUtf8($value){
		if(is_object($value)){
			return $value;
		}
		if(!is_array($value)){
			return mb_convert_encoding($value,'UTF-8','ISO-8859-2');
		}

		foreach($value as $key => $v){
			$value[$key] = self::toUtf8($v);
		}
		return $value;
	}
	
}
?>