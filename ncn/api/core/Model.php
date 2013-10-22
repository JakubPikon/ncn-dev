<?php
 
class Model {

	public $layout;
	public $view;
	public $errors = array();
	public $requestUri;
	public $referer;

    public function setView($layout, $view = NULL) {
        $this->layout = $layout;
        $this->view = $view;
    }

    public function addError($key, $value) {
        $this->errors[$key] = $value;
    }

    public function isAnyError() {
        return count($this->errors) > 0;
    }

    public function getError($fieldName) {
        return (isset($this->errors[$fieldName])) ? $this->errors[$fieldName] : NULL;
    }

    public function getErrorByKey( $key, $fieldName ) {
        return ( isset( $this->errors[$key][$fieldName] ) ) ? $this->errors[$key][$fieldName] : NULL;
    }

}