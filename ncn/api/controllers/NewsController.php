<?php
 
class NewsController implements Initializable {

	private $postDao;

	public function init( $context ){
		$this->postDao = $context->load( 'dao:PostDao' );
	}

	public function get( $context ) {
		$context->model->posts = $this->postDao->getAll('post', 0);
		for($i = 0; $i < count($context->model->posts); $i++) {
			$meta_value = $this->postDao->getMeta($context->model->posts[$i]['id']);
			if($meta_value->value) {
				$context->model->posts[$i]['meta'] = $meta_value->value; 
			}
		}
		$context->model->postmeta = $this->postDao->getAllMeta();
		$context->model->setView('', 'templates/news.html');
	}


};