<?php
 
class Context {

	public $config;
    public $session;
    public $model;

    private $holder = array();
    private $requestMethod;

	public function __construct(){
        $this->requestMethod = !empty($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : '';
        if (PHP_SAPI != 'cli'){
            $this->session = new Session();
            $this->session->start();
        }
        $this->model = new Model();
	}

    public function getRequestBody(){
        return json_decode( file_get_contents('php://input') );
    }

	public function get($key) {
        return isset($this->holder[$key]) ? $this->holder[$key] : NULL;
    }

    public function put($key, $value) {
        $this->holder[$key] = $value;
    }

    public function callMethod( $parameters ) {
        if ($parameters['class'] == NULL){
            $this->send404();
        }

        $ctlName = ucfirst($parameters['class']);
        $ctl = $this->load((!empty($parameters['ctl']) ? $parameters['ctl'] : 'ctl:') . $ctlName);

        if ($ctl == NULL){
            $this->send404();
        }

        if ($parameters['method'] != NULL && method_exists($ctl, $parameters['method'])){
            $method = $parameters['method'];
            return $ctl->$method($this);
        }

        $this->send404();
    }

    public function send404() {
        header('HTTP/1.1 404 Not found', TRUE, 404);
        exit;
    }

    public function send400( $validationErrros = array() ){
        header('Content-Type: application/json');
        header('HTTP/1.0 400 Bad request', TRUE, 400);
        echo json_encode($validationErrros);
        exit;
    }

    public function redirect($url) {
        $this->session->close();
        header('Location: '. $url, TRUE, 302);
        exit;
    }

    public function load($classId, $args = NULL) {
        static $singletonCache;

        if (!isset($singletonCache[$classId])) {
            $singletonCache[$classId] = $this->create($classId, $args);
        }

        return $singletonCache[$classId];
    }

    private function import($classId) {
        static $includesCache;

        if (isset($includesCache[$classId]))
            return $includesCache[$classId];

        $componentPaths = $this->config->componentPaths;
        $path = '';
        $parts = explode(':', $classId);
        $className = $classId;

        if (count($parts) == 2) {
            list($prefix, $className) = $parts;
            $path = $componentPaths[$prefix];
        }

        $fileName = __DIR__ . '/../' . $path . $className.'.php';

        if ( !file_exists( $fileName ) ){
            $this->send404();
        }
        require($fileName);
        $includesCache[$classId] = $className;

        return $className;
    }

    private function create($classId, $args) {
        $className = $this->import($classId);
        $object = NULL;
        if ($args != NULL){
            $object = new $className($args);
        }else{
            $object = new $className();
        }

        if ($object instanceof ContextAware){
            $object->context = $this;
        }

        if ($object instanceof SessionAware){
            $object->session = $this->session;
        }

        if ($object instanceof ConfigAware){
            $object->config = $this->config;
        }

        if ($object instanceof Initializable){
            $object->init($this);
        }

        return $object;
    }

}
