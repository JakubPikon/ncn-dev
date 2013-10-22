<?php
 
class MainController implements ContextAware, Initializable {

	private $postDao;

	public function init( $context ){
		$this->postDao = $context->load( 'dao:PostDao' );
	}

	public function index( $context ) {
		$context->model->setView('', 'templates/main.html');
	}
};