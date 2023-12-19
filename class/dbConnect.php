<?php



class Database {
  private $Host = 'localhost';
  private $User = 'root';
  private $Pwd = '';
  private $DbName = 'opepv2';
	private static $connected;
  public $pdo;

  public function __construct() {
    try {
      $this->pdo = new PDO("mysql:host=". $this->Host .";dbname=". $this->DbName, $this->User, $this->Pwd);
      $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
      die("Connection failed: " . $e->getMessage());
    }
	}
	public static function connectionCheck(){
		if(!self::$connected){
			self::$connected = new Database();
		}
		return self::$connected;
	}

	public function connect(){
		return $this->pdo;
	}
}