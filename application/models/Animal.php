<?php 
namespace Application\models; 
use Application\core\App;

class Animal 
{
    public $conn;

    public function get_nextAnimal($name)  
    {  
        $last_letter = substr($name, -2);
        $conn = App::$app->get_db(); 
        $stmt = $conn->prepare("
            SELECT  name,SUBSTRING(name, 1,1) AS name_s 
                FROM animal
                WHERE SUBSTRING(name, 1,1) = ? 
                AND status <> 1
                LIMIT 1"); 
        $stmt->bindParam(1, $last_letter);
        return $stmt;
    }
    public function update($name,$status)
    {
        $data = [
            'status' => $status,
            'name' => $name,
        ];
        $conn = App::$app->get_db();
        $status = 1;
        $stmt = $conn->prepare("
            UPDATE animal 
                SET status= :status 
                WHERE name = :name");
        $stmt->bindValue(":status", $status);
        $stmt->bindValue(":name", $name);
        $update = $stmt->execute();
        return $update;
    } 
    public function insert($name,$status)
    {
        $conn = App::$app->get_db();
        $status = 1;
        $stmt = $conn->prepare("
            INSERT INTO animal (status,name)
                VALUES (?,?) 
                ");
        $stmt->execute(array($status,$name));
    }
    public function check_if_exist($name)
    {
        $conn = App::$app->get_db();
        $stmt = $conn->prepare("
            SELECT * 
                FROM animal
                WHERE name = ?
                "); 
        $stmt->bindValue(":name", $name);
        return $stmt;
    }  
    public function check_status($name)
    {
        $conn = App::$app->get_db();
        $status = 1;
        $stmt = $conn->prepare("
            SELECT * 
                FROM animal
                WHERE name = ?
                AND status = 1
                "); 
        $stmt->bindValue(":name", $name);
        return $stmt;
    } 
    public function set_to_zero() 
    {
        $conn = App::$app->get_db();
        $status = 0;
        $stmt = $conn->prepare("
            UPDATE animal 
                SET status = :status");
        $stmt->bindValue(":status", $status);
        $update = $stmt->execute();
        return $update;
    }
}  
