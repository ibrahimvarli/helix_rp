<?php
namespace App\Core;

/**
 * Base Model Class
 * All models should extend this class
 */
abstract class Model
{
    /**
     * Database connection
     * @var \PDO
     */
    protected $db;
    
    /**
     * Table name
     * @var string
     */
    protected $table;
    
    /**
     * Primary key
     * @var string
     */
    protected $primaryKey = 'id';
    
    /**
     * Model constructor
     */
    public function __construct()
    {
        $this->connect();
    }
    
    /**
     * Connect to database
     */
    private function connect()
    {
        try {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
            $this->db = new \PDO($dsn, DB_USER, DB_PASS);
            $this->db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            $this->db->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }
    
    /**
     * Get all records
     * 
     * @param array $conditions Optional WHERE conditions
     * @param array $order Optional ORDER BY clause
     * @param int $limit Optional LIMIT clause
     * @param int $offset Optional OFFSET clause
     * @return array Results
     */
    public function getAll($conditions = [], $order = [], $limit = null, $offset = null)
    {
        // Build query
        $query = "SELECT * FROM {$this->table}";
        
        // Add WHERE clause if conditions exist
        if (!empty($conditions)) {
            $query .= " WHERE ";
            $wheres = [];
            foreach ($conditions as $column => $value) {
                $wheres[] = "$column = :$column";
            }
            $query .= implode(' AND ', $wheres);
        }
        
        // Add ORDER BY clause if order exists
        if (!empty($order)) {
            $query .= " ORDER BY ";
            $orders = [];
            foreach ($order as $column => $direction) {
                $orders[] = "$column $direction";
            }
            $query .= implode(', ', $orders);
        }
        
        // Add LIMIT and OFFSET clauses if provided
        if ($limit !== null) {
            $query .= " LIMIT :limit";
            if ($offset !== null) {
                $query .= " OFFSET :offset";
            }
        }
        
        // Prepare statement
        $stmt = $this->db->prepare($query);
        
        // Bind values for conditions
        if (!empty($conditions)) {
            foreach ($conditions as $column => $value) {
                $stmt->bindValue(":$column", $value);
            }
        }
        
        // Bind LIMIT and OFFSET if provided
        if ($limit !== null) {
            $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
            if ($offset !== null) {
                $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);
            }
        }
        
        // Execute query
        $stmt->execute();
        
        // Return results
        return $stmt->fetchAll();
    }
    
    /**
     * Get record by ID
     * 
     * @param int $id Record ID
     * @return array|false Record data or false if not found
     */
    public function getById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE {$this->primaryKey} = :id LIMIT 1");
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        return $stmt->fetch();
    }
    
    /**
     * Create new record
     * 
     * @param array $data Record data
     * @return int|false The ID of the newly created record or false on failure
     */
    public function create($data)
    {
        // Build query
        $columns = array_keys($data);
        $placeholders = array_map(function ($col) {
            return ":$col";
        }, $columns);
        
        $query = "INSERT INTO {$this->table} (" . implode(', ', $columns) . ") VALUES (" . implode(', ', $placeholders) . ")";
        
        // Prepare statement
        $stmt = $this->db->prepare($query);
        
        // Bind values
        foreach ($data as $column => $value) {
            $stmt->bindValue(":$column", $value);
        }
        
        // Execute query
        if ($stmt->execute()) {
            return $this->db->lastInsertId();
        }
        
        return false;
    }
    
    /**
     * Update existing record
     * 
     * @param int $id Record ID
     * @param array $data Record data
     * @return bool Success status
     */
    public function update($id, $data)
    {
        // Build query
        $sets = [];
        foreach (array_keys($data) as $column) {
            $sets[] = "$column = :$column";
        }
        
        $query = "UPDATE {$this->table} SET " . implode(', ', $sets) . " WHERE {$this->primaryKey} = :id";
        
        // Prepare statement
        $stmt = $this->db->prepare($query);
        
        // Bind ID
        $stmt->bindValue(':id', $id);
        
        // Bind values
        foreach ($data as $column => $value) {
            $stmt->bindValue(":$column", $value);
        }
        
        // Execute query
        return $stmt->execute();
    }
    
    /**
     * Delete record
     * 
     * @param int $id Record ID
     * @return bool Success status
     */
    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE {$this->primaryKey} = :id");
        $stmt->bindValue(':id', $id);
        return $stmt->execute();
    }
    
    /**
     * Execute custom query
     * 
     * @param string $query SQL query
     * @param array $params Query parameters
     * @return \PDOStatement Statement object
     */
    protected function query($query, $params = [])
    {
        $stmt = $this->db->prepare($query);
        $stmt->execute($params);
        return $stmt;
    }
} 