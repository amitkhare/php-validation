<?php
namespace AmitKhare\ValidBit;

class ValidBit {

	private $code;
	private $msgs;
	private $source;
    private $sanitized = array();

	function __construct(){
		$this->msgs = false;
		$this->code = 200;
	}

	public function setSource($source){
		$this->source=$source;
	}

	public function check($field="",$rules="required|numeric|min:2|max:5"){
        $rules = explode("|", $rules);
        $min = $max =0;
        if($this->is_set($field)){
            foreach ($rules as $minMax) {
                if(preg_match("/min\:[0-9]+/",$minMax)){
                    $min = (explode("n:",$minMax)) ? explode("in:",$minMax)[1] : 0 ;
                }
                if(preg_match("/max\:[0-9]+/",$minMax)){
                    $max = (explode("ax:",$minMax)) ? explode("ax:",$minMax)[1] : 0 ;
                }
            }
            foreach ($rules as $rule) {
                $this->_fetchRule($field,$rule,$min,$max);
            }
        }
	}
    // /min\:[0-9]+/
	private function  _fetchRule($field,$rule,$min=0,$max=0){
		switch($rule){
                case 'required':
                    $this->required($field);
                    break;

                case 'email':
                    $this->validateEmail($field);
                    break;

                case 'url':
                    $this->validateUrl($field);
                    break;

                case 'numeric':
                    $this->validateNumeric($field,$min,$max);
                    break;

                case 'string':
                    $this->validateString($field,$min,$max);
                break;

                case 'float':
                    $this->validateFloat($field);
                    break;

                case 'ipv4':
                    $this->validateIpv4($field);
                    break;

                case 'ipv6':
                    $this->validateIpv6($field);
                    break;

                case 'bool':
                    $this->validateBool($field);
                    break;
            }
	}

	public function setStatus($code,$msg){
			$this->code=$code;
			$this->msgs[]=$msg;
	}

	public function isValid(){
		if($this->code===200){
			return true;
		}
		return false;
	}

	public function getStatus(){
        $status = array(
                "code"=>$this->code,
                "msgs"=>$this->msgs,
                "source"=>$this->source
            );
        return $status;
    }

    public function getSanitized(){
        return $this->sanitized;
    }

	static function makeStatus($code=200,$msg="ok"){
		return array("code"=>$code,"msgs"=>array($msg));
	}

	static function ifSet($data=array(),$field="",$default=0){
		if(isset($data[$field])){
			return $field;
		}
		return $default;
	}

	private function is_set($field) {
        if(isset($this->source[$field])){
            return true;
        }else {
            $this->setStatus(500,sprintf("The `%s` field is not set.", $field));
        }
    }

    private function required($field){
        if(!isset($this->source[$field])){
            $this->setStatus(500,sprintf("The `%s` field is not set.", $field));
        } elseif(empty($this->source[$field]) || $this->source[$field]=="" || strlen($this->source[$field]) == 0){
            $this->setStatus(500,sprintf("The `%s` field is required.", $field));
        }
    }

    private function validateIpv4($field) {
        if(filter_var($this->source[$field], FILTER_VALIDATE_IP, FILTER_FLAG_IPV4) === FALSE) {
            $this->setStatus(500,$field . ' is not a valid IPv4');
        }
    }

    public function validateIpv6($field) {
        if(filter_var($this->source[$field], FILTER_VALIDATE_IP, FILTER_FLAG_IPV6) === FALSE) {
            $this->setStatus(500,$field . ' is not a valid IPv6');
        }
    }

    private function validateFloat($field) {
        if(filter_var($this->source[$field], FILTER_VALIDATE_FLOAT) === false) {
            $this->setStatus(500,$field . ' is an invalid float');
        }
    }

    private function validateString($field,$min=0,$max=0) {
        if(isset($this->source[$field])) {

            if(!is_string($this->source[$field])) {
                $this->setStatus(500, $field . ' is invalid string');
                $this->sanitizeString($field);
            }

            if ($min!==0){
                if(strlen($this->source[$field]) < $min) {
                    $this->setStatus(500,$field . ' is too short');
                    $this->sanitizeString($field);
                }
            }
            if ($max!==0){
                if(strlen($this->source[$field]) > $max) {
                    $this->setStatus(500,$field . ' is too long');
                    $this->sanitizeString($field);
                }
            }
        }
    }

    private function validateNumeric($field, $min=0, $max=0) {

        if(preg_match("/[^0-9]+/",$this->source[$field])) {
            $this->setStatus(500,$field . ' is an invalid number');
            $this->sanitizeNumeric($field);
        } else {
            if ($max!==0){
                if(strlen($this->source[$field]) > $max) {
                    $this->setStatus(500,$field . ' is too long, it should not more than '.$max.' characters.');
                    $this->sanitizeString($field);
                }
            }
            if ($min!==0){
                if(strlen($this->source[$field]) < $min) {
                    $this->setStatus(500,$field . ' is too short, it should be at least '.$min.' characters long.');
                    $this->sanitizeString($field);
                }
            }
        }
    }

    private function validateUrl($field) {
        if(filter_var($this->source[$field], FILTER_VALIDATE_URL) === FALSE) {
            $this->setStatus(500,$field . ' is not a valid URL');
            $this->sanitizeUrl($field);
        }
    }

    private function validateEmail($field) {
        if(filter_var($this->source[$field], FILTER_VALIDATE_EMAIL) === FALSE) {
            $this->setStatus(500,$field . ' is not a valid email address');
            $this->sanitizeEmail($field);
        }
    }

    private function validateBool($field) {
        filter_var($this->source[$field], FILTER_VALIDATE_BOOLEAN);{
            $this->setStatus(500,$field . ' is Invalid');
        }
    }

    public function sanitizeEmail($field) {
        $email = preg_replace( '((?:\n|\r|\t|%0A|%0D|%08|%09)+)i' , '', $this->source[$field] );
        $this->sanitized[$field] = (string) filter_var($email, FILTER_SANITIZE_EMAIL);
    }

    private function sanitizeUrl($field) {
        $this->sanitized[$field] = (string) filter_var($this->source[$field],  FILTER_SANITIZE_URL);
    }

    private function sanitizeNumeric($field) {
        $this->sanitized[$field] = (int) filter_var($this->source[$field], FILTER_SANITIZE_NUMBER_INT);
    }

    private function sanitizeString($field) {
        $this->sanitized[$field] = (string) filter_var($this->source[$field], FILTER_SANITIZE_STRING);
    }

}