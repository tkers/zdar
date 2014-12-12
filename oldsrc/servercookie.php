<?php
/*
 *  File: servercookie.php
 *  Author: Tijn Kersjes
 *  Last Modied: May 26, 2010
 *
 *  The Servercookie class enables you to easily save and read data
 *  to a file on the server in situations you don't feel like
 *  using a database. Requires a file to store the data with the
 *  filepermissions set to 777.
 *
 *  Quick reference:
 *
 *  + exists(string $key)
 *    returns whether the given key exists
 *
 *  + read(string $key)
 *    returns the value of the given key (or a blank string when
 *    the key does not exist)
 *
 *  + write(string $key, mixed $value)
 *    sets the value of the given key. The value can be a
 *    string, an integer, an array, or a boolean
 *
 *  Example:
 *
 *  include('servercookie.php');
 *  $storage=new Servercookie('database.txt')
 *  $storage->write('name','Tijn');
 *  echo $storage->read("name");
 *
 */

class Servercookie{
	private $storagelocation;
	private $storagedata;
	
	public function __construct($filename){
		if($this->accessible($filename)){
			$this->storagelocation=$filename;
			$this->load();
		}
		else{
			trigger_error('Servercookie('.$filename.'): Unable to use file: Permission denied', E_USER_ERROR);
		}
	}
	
	//loads the data from the file (one time only)
	private function load(){
		$contents=file_get_contents($this->storagelocation);
		$this->storagedata=json_decode($contents,true);
		if($this->storagedata==''){
			$this->storagedata=array();
		}
	}
	
	//returns whether the file permissions are set to 777
	private function accessible($file){
		$perm=substr(sprintf('%o',fileperms($file)),-4);
		return ($perm=='0777');
	}
	
	private function save(){
		$contents=json_encode($this->storagedata);
		$file=fopen($this->storagelocation,'w');
		fwrite($file,$contents);
		fclose($file);
	}
	
	//reads a key
	public function read($name){
		if($this->exists($name)){
			return $this->storagedata[$name];
		}
		else{
			return '';
		}
	}
	
	//sets the value of a key
	public function write($name,$value){
		$this->storagedata[$name]=$value;
		$this->save();
	}
	
	public function delete($name){
		unset($this->storagedata[$name]);
		$this->save();
	}
	
	//returns whether the key exists
	public function exists($name){
		return (array_key_exists($name,$this->storagedata));
	}
}