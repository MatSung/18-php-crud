<?php
class DatabaseConnection{
    private $host = "localhost";
    private $user = "root";
    private $password = "";
    //edit database on command
    private $database = "parduotuve";

    protected $conn;

    public function __construct() {
        try{
            
            $this->conn = new PDO("mysql:host=$this->host;dbname=$this->database", $this->user, $this->password);
            // not fail
        }
        catch (PDOException $e){
            // fail
        }
    }

    //pick a table and select all from it
    public function selectAction($table, $sortCol="id", $sortDir="ASC", $filter = "1"){
        try {
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "SELECT * FROM `$table` WHERE $filter ORDER BY $sortCol $sortDir";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $result = $stmt->fetchAll();
            return $result;
        } catch(PDOException $e) {
            return "Failed: " . $e->getMessage();
        }
    }

    public function selectByColAction($table, $col, $filter = 1){
        try {
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "SELECT $col FROM `$table` WHERE $filter";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $result = $stmt->fetchAll();
            return $result;
        } catch(PDOException $e) {
            return "Failed: " . $e->getMessage();
        }
    }

        //$table1 = "products";
        //$table2 = "categories";

        //$table1RelationCol = "category_id";
        //$table2RelationCol = "id";

        //$join = "LEFT JOIN";

        //$cols = ["products.id", "products.title", "products.description", "products.price", "categories.title as Category", products.image_url];
    public function selectJoinAction($table1, $table2, $table1RelationCol, $table2RelationCol, $join, $cols, $sortCol = "id", $sortDir = "ASC", $filter = "1"){
        $cols = implode(",", $cols);

        try{
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql= "SELECT $cols FROM $table1 
            $join $table2
            ON $table1.$table1RelationCol = $table2.$table2RelationCol
            WHERE $filter
            ORDER BY $sortCol $sortDir";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $result = $stmt->fetchAll();
            
            return $result; 
        } catch(PDOException $e) {
            return "Failed " . $e->getMessage();
        }
    }

    // SELECT $cols FROM $table1 WHERE $col = $selection
    // and join and no sort

    public function insertAction($table, $cols, $values){
        //paima table, column, value ir ideda i sql
        $cols = implode(",", $cols);
        $values = implode(",", $values);

        try{
        $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        //INSERT INTO `products`([`title`,`description`, `price`, `category_id`]) VALUES ('title','description', 1, 1)
        $sql = "INSERT INTO `$table` ($cols) VALUES ($values)";

        $this->conn->exec($sql);
        return 1;
        //return "put " . $values . " into " . $cols . " into table " . $table;
        } catch (PDOException $e) {
            echo "failed: " . $e->getMessage(); 
            //failed
            return 0;
        }
        
    }

    public function deleteAction($table, $id){
        try {
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "DELETE FROM `$table` WHERE id = $id";
            $this->conn->exec($sql);
        }
        catch(PDOException $e) {
            echo "Failed: " . $e->getMessage();
        }
    }

    public function selectOneAction($table, $id) {
        try {
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "SELECT * FROM `$table` WHERE id = $id";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
            $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
            $result = $stmt->fetchAll();
            return $result;
        } catch(PDOException $e) {
            return "Failed: " . $e->getMessage();
        }
    }

    public function updateAction($table, $id, $data) {
        $cols = array_keys($data);
        //var_dump($cols);
        $values = array_values($data);
        //var_dump($values);

        $dataString = [];
        for ($i=0; $i<count($cols); $i++) {
           $dataString[] = $cols[$i] . " = '" . $values[$i]. "'";
        }
        $dataString = implode(",", $dataString);
        //var_dump($dataString);


       try{
              $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
              $sql = "UPDATE `$table` SET $dataString WHERE id = $id";
              $stmt = $this->conn->prepare($sql);
              $stmt->execute();
              //echo "Pavyko atnaujinti irasa";
         } 
       catch(PDOException $e) {
              echo "Failed: " . $e->getMessage();
       }
    }

    //Destruktoriaus funkcija - pasileidzia automatiskai po objekto sunaikinimo/ ir po objekto metodo ivykdymo
    public function __destruct() {
        $this->conn = null;
       // echo "Atjungta is duomenu bazes sekmingai";
    }
}
?>