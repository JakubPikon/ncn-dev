<?php
 
class GalleryController implements ContextAware, Initializable {

	private $postDao;

	public function init( $context ){
		$this->postDao = $context->load( 'dao:PostDao' );
	}

	public function get( $context ) {
		$images = $this->postDao->getImages();
		for($i=0; $i<count($images); $i++) {
			$context->imagesmeta[$i] = unserialize($images[$i]['meta_value']);
		}

		$context->model->setView('', 'templates/gallery.html');
	}
};