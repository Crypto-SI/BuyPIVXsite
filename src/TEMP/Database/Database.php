<?php

/**
 *------------------------------------------------------------------------------
 *
 *  PDO Database Wrapper
 *
 *  Manages The Database Connection And Simplifies Database Interaction.
 *
 */

namespace TEMP\Database;

use PDO;
use PDOException;
use Exception;

class Database {


    // Connection/Name
    private $pdo;
    private $name;


    // Default Configuration
    private $configuration  = [
        'charset'       => 'utf8mb4',
        'dbdriver'      => 'mysql'
    ];

    // Connection Options
    private $options = [
        PDO::ATTR_CASE => PDO::CASE_NATURAL,
        PDO::ATTR_EMULATE_PREPARES => false,
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_ORACLE_NULLS => PDO::NULL_NATURAL,
        PDO::ATTR_PERSISTENT => true,
        PDO::ATTR_STRINGIFY_FETCHES => false
    ];

    // Credentials Error Messages
    private $error          = [
        'credentials'   => 'Misconfigured Database Credentials: Host, Name,
                            Username, Or Password Was Not Defined',

        'connection'    => 'Database Connection Failed!'
    ];


    /**
     *  Define Class Dependencies And Connect To Database
     *
     *  @param object $builder          Database Query Builder Helper
     *  @param array  $credentials      Database Credentials
     *  @param array  $configuration    Database Configuration
     */
    public function __construct(QueryBuilder $builder, $credentials, $configuration = []) {
        $this->build = $builder;

        // Connect To Database
        $this->connect($credentials, $configuration);
    }


    /**
     *  Connect To Database
     *
     *  @param array $credentials       Assoc Array Containing DB Credentials
     *  @param array $configuration     Assoc Array Containing DB Configuration
     */
    private function connect($credentials, $configuration = []) {
        try {

            // Error Check
            if (!isset($credentials['host'],
                       $credentials['name'],
                       $credentials['username'],
                       $credentials['password']
                      )
            ) {
                throw new Exception($this->error['credentials']);
            }

            // Attempt PDO DB Connection
            $this->pdo  = new PDO(
                $this->buildDSN($credentials, $configuration),
                $credentials['username'],
                $credentials['password'],
                $this->options
            );

            // On Successful Connection Set Database Name ( Needed Within Next Insert ID Method )
            $this->name = $credentials['name'];
        }
        catch (PDOException $e) {
            throw new Exception($e);
            throw new Exception($this->error['connection']);
        }
    }


    /**
     *  Build DSN String
     *
     *  @param  array $credentials      Database Credentials
     *  @return string                  Formatted DSN
     */
    private function buildDSN($credentials, $configuration = []) {
        $configuration = array_merge($this->configuration, (array) $configuration);

        switch ($configuration['dbdriver']) {
            case 'mysql':
                return 'mysql:host=' . $credentials['host'] . ';dbname=' . $credentials['name'] . ';charset=' . $configuration['charset'] .';';
                break;
        }
    }


    /**
     *  Fetch
     *
     *  @see                            $this->select Comments
     */
    public function fetch($table, $where = [], $rules = '', $column = '*') {
        return $this->select($table, $where, $rules, $column, 'fetch');
    }


    /**
     *  FetchAll
     *
     *  @see                            $this->select Comments
     */
    public function fetchAll($table, $where = [], $rules = '', $column = '*') {
        return $this->select($table, $where, $rules, $column, 'fetchAll');
    }


    /**
     *  Select
     *
     *  @param  string $table           Database Table Being Used
     *  @param  array  $where           Columns To Use As Filter + Data Used To Filter
     *  @param  string $rules           Additional SQL Rules To Be Set
     *  @param  string $column          Set Columns To Be Returned
     *  @param  string $type            Method Type To Use ( fetchAll | fetch )
     *  @return array                   Data Received From DB
     */
    public function select($table, $where, $rules = '', $column = '*', $type) {
        $sql    = $this->build->sqlSelect($column, $table, $where, $rules);
        $params = $this->build->placeholder($where);

        return $this->execute($sql, $params, $type);
    }


    /**
     *  Insert
     *
     *  @param string $table            Database Table Being Used
     *  @param array  $data             ['Column' => 'Data To Insert']
     */
    public function insert($table, $data) {
        $sql    = $this->build->sqlInsert($table, $data);
        $params = $this->build->placeholder($data);
        return $this->execute($sql, $params, 'lastInsertId');
    }


    /**
     *  Update
     *
     *  @param  string $table           Database Table Being Used
     *  @param  array  $data            Columns To Update + Data Used For Update
     *  @param  string $where           Columns To Filter + Data Used For Filter
     *  @param  string $rules           Additional SQL Rules To Be Set
     *  @return array                   Count Of Rows Affected
     */
    public function update($table, $data, $where = [], $rules = '') {
        if (!$where && !$rules) {  return;  }

        $sql    = $this->build->sqlUpdate($table, $data, $where, $rules);
        $params = $this->build->placeholder(array_merge($data, $where));
        return $this->execute($sql, $params, 'rowCount');
    }


    /**
     *  Delete
     *
     *  @param  string $table           Database Table Being Used
     *  @param  string $where           Columns To Filter + Data Used For Filter
     *  @param  string $rules           Additional SQL Rules To Be Set
     *  @return array                   Count Of Rows Affected
     */
    public function delete($table, $where = [], $rules = '') {
        if (!$where && !$rules) {  return;  }

        $sql    = $this->build->sqlDelete($table, $where, $rules);
        $params = $this->build->placeholder($where);
        return $this->execute($sql, $params, 'rowCount');
    }


    /**
     *  Count
     *
     *  @param  string $table           Database Table Being Used
     *  @param  string $where           Columns To Filter + Data Used For Filter
     *  @param  string $rules           Additional SQL Rules To Be Set
     *  @return array                   Row Count
     */
    public function count($table, $where = [], $rules = '') {
        return $this->fetch($table, $where, $rules, 'COUNT(*)')['COUNT(*)'];
    }


    /**
     *  Execute Raw Sql Entry
     *
     *  @param  string $sql             Custom SQL To Run
     *  @param  string $params          Params Used As Placeholders
     *  @param  string $type            Action To Return
     *  @return array                   Data Retrieved
     */
    public function execute($sql, $params = [], $type = 'fetchAll') {
        $stmt = $this->pdo->prepare((string) $sql);
        $stmt->execute((array) $params);

        if (method_exists($stmt, $type)) {
            return $stmt->{$type}();
        } elseif (method_exists($this->pdo, $type)) {
            return $this->pdo->{$type}();
        }
    }


    /**
     *  Last Insert ID
     *
     *  @return string                  ID Of Recently Inserted Row
     */
    public function lastInsertId() {
        return $this->pdo->lastInsertId();
    }


    /**
     *  Next Insert ID
     *
     *  @param  string $table           Database Table Being Used
     *  @return array                   Next auto_increment ID For Specific Table
     */
    public function nextInsertId($table) {
        return $this->fetch('information_schema.tables', [
            'table_schema'  => (string) $this->name,
            'table_name'    => (string) $table
        ], '', 'auto_increment')['auto_increment'];
    }


    /**
     *  Direct Access To PDO Class
     *
     *  @return object                  Connected PDO Class Instance
     */
    public function pdo() {
        return $this->pdo;
    }


    /**
     *  Transactions
     *  Begin, End, And Rollback Transaction
     */
    public function beginTransaction()      {   $this->pdo->beginTransaction();   }
    public function endTransaction()        {   $this->pdo->commit();             }
    public function rollBackTransaction()   {   $this->pdo->rollBack();           }
}
