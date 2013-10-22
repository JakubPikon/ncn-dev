<?php
 
abstract class BaseDao implements Initializable {

    protected $db;
    protected $tblPrefix;

    public function init($context) {
        $dbOptions = $context->config->dbOptions;
        $this->tblPrefix = $dbOptions['tbl_prefix'];
        $this->db = $context->get('db');
        if ($this->db == null) {
            $this->db = new \PDO(
                'mysql:host=' . $dbOptions['host'] . ';dbname=' . $dbOptions['database'],
                $dbOptions['user'],
                $dbOptions['password'],
                $context->config->pdoOptions);
            $this->db->exec('SET NAMES utf8');
            $context->put('db', $this->db);
        }
    }

	public function begin() {
		$this->db->beginTransaction();
	}

	public function commit() {
		$this->db->commit();
	}

	public function rollback() {
		$this->db->rollBack();
	}
}