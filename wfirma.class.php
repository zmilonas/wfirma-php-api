<?php
class wfirmaQuery {
	private $WFIRMA_LOGIN = "";
	private $WFIRMA_PASSW = "";
	public $request = [];
	public $module;
	public $path;

	public function __construct($module,$call) {
		$this->path = $module.'/'.$call.'?inputFormat=json&outputFormat=json';
		$this->module = $module;
		$this->request[$module]["parameters"] = [];
	}
	public function execute() {
		$ch = curl_init();
		$path = $this->path;
		$request = json_encode($this->request, JSON_FORCE_OBJECT);
		curl_setopt($ch, CURLOPT_URL, "https://api2.wfirma.pl/{$path}");
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
		curl_setopt($ch, CURLOPT_USERPWD, $this->WFIRMA_LOGIN . ':' . $this->WFIRMA_PASSW);
		return curl_exec($ch);
	}
	public function setParameter($key, $value) {
		$v = is_int($value) ? (string)$value : $value;
		$this->request[$this->module]["parameters"][(string)$key] = $v;
	}
	public function addCondition($field, $op, $val) {
		$p = "parameters";
		$c = "conditions";
		$m = $this->module;
		$u = array_key_exists($c, $this->request[$m][$p]) ? count($this->request[$m][$p][$c]) : "0";
		$this->request[$m][$p][$c][$u]["condition"] = array(
					"field" => $field,
					"operator" => $op,
					"value" => $val
					);
	}
	public function setOrder($field, $way) {
		$this->parameter("order", array( $way => $field));
	}
	public function setFields($arr) {
		foreach($arr as $field) {
			$p = "parameters";
			$c = "fields";
			$m = $this->module;
			$u = array_key_exists($c, $this->request[$m][$p]) ? count($this->request[$m][$p][$c]) : "0";
			$this->request[$m][$p][$c][$u]["field"] = $field;
		}
	}
	public function printRequest() {
		return json_encode($this->request, JSON_FORCE_OBJECT);
	}
}