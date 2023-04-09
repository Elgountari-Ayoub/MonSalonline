<?php
/* 
   *  PDO DATABASE CLASS
   *  Connects Database Using PDO
	 *  Creates Prepeared Statements
	 * 	Binds params to values
	 *  Returns rows and results
   */
class Database
{
	private $host = DB_HOST;
	private $user = DB_USER;
	private $pass = DB_PASS;
	private $dbname = DB_NAME;

	private $dbh;
	private $error;
	private $stmt;

	public function __construct()
	{
		// Set DSN
		$dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbname;
		$options = array(
			PDO::ATTR_PERSISTENT => true,
			PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
		);

		// Create a new PDO instanace
		try {
			$this->dbh = new PDO($dsn, $this->user, $this->pass, $options);
		}		// Catch any errors
		catch (PDOException $e) {
			$this->error = $e->getMessage();
			return $this->error;
		}
	}

	// Prepare statement with query
	public function query($query)
	{
		try {
			$this->stmt = $this->dbh->prepare($query);
		} catch (PDOException $e) {
			$this->error = $e->getMessage();
			return $this->error;
		}
	}

	// Bind values
	public function bind($param, $value, $type = null)
	{
		try {
			if (is_null($type)) {
				switch (true) {
					case is_int($value):
						$type = PDO::PARAM_INT;
						break;
					case is_bool($value):
						$type = PDO::PARAM_BOOL;
						break;
					case is_null($value):
						$type = PDO::PARAM_NULL;
						break;
					default:
						$type = PDO::PARAM_STR;
				}
			}
			$this->stmt->bindValue($param, $value, $type);
		} catch (PDOException $e) {
			$this->error = $e->getMessage();
			return $this->error;
		}
	}

	// Execute the prepared statement
	public function execute()
	{
		try {
			return $this->stmt->execute();
		} catch (PDOException $e) {
			$this->error = $e->getMessage();
			return $this->error;
		}
	}

	// Get result set as array of objects
	public function resultset()
	{
		try {
			$this->execute();
			return $this->stmt->fetchAll(PDO::FETCH_OBJ);
		} catch (PDOException $e) {
			$this->error = $e->getMessage();
			return $this->error;
		}
	}

	// Get single record as object
	public function single()
	{
		try {
			$this->execute();
			return $this->stmt->fetch(PDO::FETCH_OBJ);
		} catch (PDOException $e) {
			$this->error = $e->getMessage();
			return $this->error;
		}
	}

	// Get record row count
	public function rowCount()
	{
		try {
			return $this->stmt->rowCount();
		} catch (PDOException $e) {
			$this->error = $e->getMessage();
			return $this->error;
		}
	}

	// Returns the last inserted ID
	public function lastInsertId()
	{
		try {
			return $this->dbh->lastInsertId();
		} catch (PDOException $e) {
			$this->error = $e->getMessage();
			return $this->error;
		}
	}
}
