<?php
require_once 'Zend/Db.php';
require_once 'Zend/Exception.php';
#require_once (LIB_PATH .  '/managerLogger.php');

class DbConn
{

	protected $_db;
    private static $objDb = NULL;
    protected $_log;

    public static function getInstance() {

        if ( is_null( DbConn::$objDb ) ) {
            DbConn::$objDb = new DbConn();
        }
        return DbConn::$objDb;
    }

	public function __construct()
	{
        $params = array(
        			    'host'  => 'localhost',
                        'username' => 'root',
                        'password' => '',
                        'dbname'   => 'regurume'
                  );

        $this->_db = Zend_Db::factory('Pdo_mysql', $params);
        $this->_db->setFetchMode(Zend_Db::FETCH_ASSOC);
        $this->_db->getProfiler()->setEnabled(true);
        //Zend_Db_Table::setDefaultAdapter($this->_db);

        $res =$this->_db->getConnection();
        #$this->_log = managerLogger::getInstance();
	}//--end:function

    public function selectPlaceQuery($sql, $param = NULL, $excpMode = TRUE) {
        $profiler = $this->_db->getProfiler(Zend_Db_Profiler::INSERT);
        if ($excpMode) {
            try{
                $result = $this->_db->fetchAll($sql, $param);
                //$query = $profiler->getLastQueryProfile();
                //$this->_log->logSql($query->getQuery().print_r($param, TRUE));
            } catch (Zend_Db_Exception $e) {
                $this->_log->err($e->getMessage().print_r($param,TRUE));
                throw $e;
            }
        } else {
            $result = $this->_db->fetchAll($sql, $param);
            //$query = $profiler->getLastQueryProfile();
            //$this->_log->logSql($query->getQuery().print_r($param, TRUE));
        }
        return $result;
    }//--end:function

    public function executeSql($sql , $para)
    {
         $stmt = $this->_db->prepare($sql);
         $result = $stmt->execute($para);
         $res = $stmt->rowCount();
         return $res;
    }
    public function insertData($table, $param, $excpMode = TRUE)
    {
        $profiler = $this->_db->getProfiler(Zend_Db_Profiler::INSERT);
        if ($excpMode) {

            try {
               $result = $this->_db->insert($table,$param);
               $query = $profiler->getLastQueryProfile();
                return true;
            } catch(Zend_Db_Exception $e){
               $this->_log->err($e->getMessage().print_r($param,TRUE));
               throw $e;
               return false;
            }
        } else {

            $result = $this->_db->insert($table,$param);
            if ($result === FALSE) {
                $this->_log->err(print_r($param,true));
            } else {
                $query = $profiler->getLastQueryProfile();
                $this->_log->logSql($query->getQuery().print_r($param,true));
            }
        }
        return $result;
    }

    public function updateData($table, $data, $target, $excpMode = TRUE)
    {
        $profiler = $this->_db->getProfiler(Zend_Db_Profiler::UPDATE);
        if ($excpMode) {
        
            try{
                $result = $this->_db->update($table,$data,$target);
                $query = $profiler->getLastQueryProfile();
            } catch(Zend_Db_Exception $e){
                //echo $e->getMessage();
                $this->_log->err($e->getMessage().print_r($param,TRUE));
                throw $e;
                return FALSE;
            }
        } else {
           
            $result = $this->_db->update($table,$data,$target);
        }
        return $result;
    }

    public function deleteData($table, $target,$excpMode = TRUE)
    {
        $profiler = $this->_db->getProfiler(Zend_Db_Profiler::DELETE);
        if ($excpMode) {
       
            try {
                $result = $this->_db->delete($table, $target);
                $query = $profiler->getLastQueryProfile();
            } catch(Zend_Db_Exception $e) {
                $this->_log->err($e->getMessage().print_r($param,TRUE));
                throw $e;
                //echo $e->getMessage();
                return FALSE;
            }
        } else {

            $result = $this->_db->delete($table, $target);
        }
        return $result;
    }


    public function dbDisconnect() {
        $this->_db->closeConnection();
    }

 
    public function beginTransaction() {
        $this->_db->beginTransaction();
        //$this->_db->query('begin');
    }

    public function commit() {
        $this->_db->commit();
        //$this->_db->query('commit');
    }

    public function rollBack() {
        $this->_db->rollBack();
        //$this->_db->query('rollback');
    }

    public function lastInsertId() {
        return $this->_db->lastInsertId();
    }

    public function quoteInto( $sql, $param ) {
        return $this->_db->quoteInto( $sql, $param );
    }

}//============================ END::CLASS
