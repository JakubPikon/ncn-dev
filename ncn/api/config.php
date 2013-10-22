<?php
 

define( 'SEC_AUTH_OBJECT', 'sec_authObject' );
define( 'SEC_REQUEST_URI', 'sec_requestUri' );
define( 'SESSION_EXPIRATION_TIME', 3600 );

class Config {

    public $dbOptions = array(
        'host'       => 'localhost',
        'user'       => 'ncn',
        'password'   => 'ncn',
        'database'   => 'ncn',
        'tbl_prefix' => 'wp_'
    );

    public $pdoOptions = array(
        \PDO::ATTR_PERSISTENT => true,
        \PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true,
        \PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
        \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION
    );

    public $componentPaths = array(
        'core'        => 'core/',
        'ctl'         => 'controllers/',
        'dao'         => 'database/',
        'util'        => 'util/'
    );

    public function __construct( $tblPrefix ){
        if ( $tblPrefix ){
            $this->dbOptions['tbl_prefix'] = 'wp_';
        }
    }
};