<?php
class database
{
    private $dbConnection; 
    /**
     * Makes a db connection
     */
    public function __construct($dbName)
    {
        $this->setDBConnection($dbName);
    }

    public function setDBConnection($dbName)
    {
        try {
            $this->dbConnection = new PDO('sqlite:'.$dbName);
            $this->dbConnection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Database Connection Error: " . $e->getMessage();
            exit();
        }
    }

    /**
     * Prepares an SQL query for execution
     *
     * string $query The SQL query to prepare
     *
     * returns the prepared statement
     */
    public function prepare($query)
    {
        // Use the PDO prepare method to prepare the SQL query
        return $this->dbConnection->prepare($query);
    }

    /**
     * Executes Prepared sql statement
     */
    public function executeSQL($sql, $params=[])
    {
        $stmt = $this->dbConnection->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}