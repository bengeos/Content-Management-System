<?php
/**
 * Created by PhpStorm.
 * User: BENGEOS-PC
 * Date: 8/17/2015
 * Time: 9:02 AM
 */
require_once "DB_Access.php";
class My_Database {
    private $connection;
    private $database;

    public function __construct()
    {
        $this->database = new DB_Access();
        $this->connection = $this->database->pass_connection();
        $this->connection->query("USE content_manager"); // datebase selection
    }
    public function add_material($user_id,$material_name,$grade_id,$subject_d,$category,$file_name,$description)
    {
        $res = array();
        $res['status'] = 1;
        try {
            $sql = "INSERT INTO materials(user_id,name,grade_id,subject_id,category,file_name,description) VALUE (:id,:name,:g_id,:s_id,:cat,:file,:desc)";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':id', $user_id, PDO::PARAM_INT);
            $stmt->bindvalue(':name', $material_name, PDO::PARAM_STR);
            $stmt->bindvalue(':g_id', $grade_id, PDO::PARAM_INT);
            $stmt->bindvalue(':s_id', $subject_d, PDO::PARAM_INT);
            $stmt->bindvalue(':cat', $category, PDO::PARAM_STR);
            $stmt->bindvalue(':file', $file_name, PDO::PARAM_STR);
            $stmt->bindvalue(':desc', $description, PDO::PARAM_STR);
            $stmt->execute();
        } catch (PDOException $e) {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }
    public function get_materials_by_user_id($user_id)
    {
        $res = array();
        $res['status'] = 1;
        try{
            $sql = "SELECT * FROM materials WHERE user_id=:u_id ORDER BY grade_id,subject_id";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':u_id', $user_id, PDO::PARAM_INT);
            $stmt->execute();
            $res['result'] = array();
            $set = array();
            while($row = $stmt->fetch())
            {
                $set[] = $row;
            }
            $res['result'] = $set;
        }catch(PDOException $e)
        {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }
    public function get_materials_by_grade($grade_id)
    {
        $res = array();
        $res['status'] = 1;
        try{
            $sql = "SELECT * FROM materials WHERE grade_id=:g_id";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':g_id', $grade_id, PDO::PARAM_INT);
            $stmt->execute();
            $res['result'] = array();
            $set = array();
            while($row = $stmt->fetch())
            {
                $set[] = $row;
            }
            $res['result'] = $set;
        }catch(PDOException $e)
        {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }
    public function get_materials_by_grade_and_category($grade_id,$category)
    {
        $res = array();
        $res['status'] = 1;
        try{
            $sql = "SELECT * FROM materials WHERE grade_id=:g_id AND category=:cat";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':g_id', $grade_id, PDO::PARAM_INT);
            $stmt->bindvalue(':cat', $category, PDO::PARAM_INT);
            $stmt->execute();
            $res['result'] = array();
            $set = array();
            while($row = $stmt->fetch())
            {
                $set[] = $row;
            }
            $res['result'] = $set;
        }catch(PDOException $e)
        {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }
    public function get_materials_by_grade_and_subject_and_category($grade_id,$subject_id,$category)
    {
        $res = array();
        $res['status'] = 1;
        try{
            $sql = "SELECT * FROM materials WHERE grade_id=:g_id AND subject_id=:s_id AND category=:cat";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':g_id', $grade_id, PDO::PARAM_INT);
            $stmt->bindvalue(':s_id', $subject_id, PDO::PARAM_INT);
            $stmt->bindvalue(':cat', $category, PDO::PARAM_INT);
            $stmt->execute();
            $res['result'] = array();
            $set = array();
            while($row = $stmt->fetch())
            {
                $set[] = $row;
            }
            $res['result'] = $set;
        }catch(PDOException $e)
        {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }
    public function get_materials_category_for_grade_and_category($grade_id,$category)
    {
        $res = array();
        $res['status'] = 1;
        try{
            $sql = "SELECT * FROM materials WHERE grade_id=:g_id AND category=:cat GROUP BY category ORDER BY subject_id";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':g_id', $grade_id, PDO::PARAM_INT);
            $stmt->bindvalue(':cat', $category, PDO::PARAM_INT);
            $stmt->execute();
            $res['result'] = array();
            $set = array();
            while($row = $stmt->fetch())
            {
                $set[] = $row;
            }
            $res['result'] = $set;
        }catch(PDOException $e)
        {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }
    public function get_materials_category_for_grade_and_subject_and_category($grade_id,$subject_id,$category)
    {
        $res = array();
        $res['status'] = 1;
        try{
            $sql = "SELECT * FROM materials WHERE grade_id=:g_id AND subject_id=:s_id AND category=:cat GROUP BY category ORDER BY subject_id";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':g_id', $grade_id, PDO::PARAM_INT);
            $stmt->bindvalue(':s_id', $subject_id, PDO::PARAM_INT);
            $stmt->bindvalue(':cat', $category, PDO::PARAM_INT);
            $stmt->execute();
            $res['result'] = array();
            $set = array();
            while($row = $stmt->fetch())
            {
                $set[] = $row;
            }
            $res['result'] = $set;
        }catch(PDOException $e)
        {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }
    public function get_material_by_id($id)
    {
        $res = array();
        $res['status'] = 1;
        try{
            $sql = "SELECT * FROM materials WHERE id=:m_id";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':m_id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $res['result'] = $stmt->fetch();
        }catch(PDOException $e)
        {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }
    public function get_material_category_for_grade($grade_id)
    {
        $res = array();
        $res['status'] = 1;
        try{
            $sql = "SELECT * FROM materials WHERE grade_id=:g_id GROUP BY category ORDER BY subject_id";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':g_id', $grade_id, PDO::PARAM_INT);
            $stmt->execute();
            $res['result'] = array();
            $set = array();
            while($row = $stmt->fetch())
            {
                $set[] = $row;
            }
            $res['result'] = $set;
        }catch(PDOException $e)
        {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }
    public function get_material_category_for_grade_and_subject($grade_id,$subject_id)
    {
        $res = array();
        $res['status'] = 1;
        try{
            $sql = "SELECT * FROM materials WHERE grade_id=:g_id AND subject_id=:s_id GROUP BY category ORDER BY subject_id";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':g_id', $grade_id, PDO::PARAM_INT);
            $stmt->bindvalue(':s_id', $subject_id, PDO::PARAM_INT);
            $stmt->execute();
            $res['result'] = array();
            $set = array();
            while($row = $stmt->fetch())
            {
                $set[] = $row;
            }
            $res['result'] = $set;
        }catch(PDOException $e)
        {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }
    public function get_materials_by_grade_and_subject($grade_id,$subject_id)
    {
        $res = array();
        $res['status'] = 1;
        try{
            $sql = "SELECT * FROM materials WHERE grade_id=:g_id AND subject_id=:s_id";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':g_id', $grade_id, PDO::PARAM_INT);
            $stmt->bindvalue(':s_id', $subject_id, PDO::PARAM_INT);
            $stmt->execute();
            $res['result'] = array();
            $set = array();
            while($row = $stmt->fetch())
            {
                $set[] = $row;
            }
            $res['result'] = $set;
        }catch(PDOException $e)
        {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }
    public function add_grade($user_id,$name)
    {
        $res = array();
        $res['status'] = 1;
        try {
            $sql = "INSERT INTO grades(user_id,name) VALUE (:u_id,:g_name)";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':u_id', $user_id, PDO::PARAM_INT);
            $stmt->bindvalue(':g_name', $name, PDO::PARAM_STR);
            $stmt->execute();
        } catch (PDOException $e) {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }
    public function get_grade_by_id($grade_id)
    {
        $res = array();
        $res['status'] = 1;
        try{
            $sql = "SELECT * FROM grades WHERE id=:g_id";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':g_id', $grade_id, PDO::PARAM_INT);
            $stmt->execute();
            $res['result'] = $stmt->fetch();
        }catch(PDOException $e)
        {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }
    public function add_subject($user_id,$name)
    {
        $res = array();
        $res['status'] = 1;
        try {
            $sql = "INSERT INTO subjects(user_id,name) VALUE (:u_id,:s_name)";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':u_id', $user_id, PDO::PARAM_INT);
            $stmt->bindvalue(':s_name', $name, PDO::PARAM_STR);
            $stmt->execute();
        } catch (PDOException $e) {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }
    public function get_grades()
    {
        $res = array();
        $res['status'] = 1;
        try{
            $sql = "SELECT * FROM grades";
            $stmt = $this->connection->prepare($sql);
            $stmt->execute();
            $res['result'] = array();
            $set = array();
            while($row = $stmt->fetch())
            {
                $set[] = $row;
            }
            $res['result'] = $set;
        }catch(PDOException $e)
        {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }
    public function get_subjects()
    {
        $res = array();
        $res['status'] = 1;
        try{
            $sql = "SELECT * FROM subjects";
            $stmt = $this->connection->prepare($sql);
            $stmt->execute();
            $res['result'] = array();
            $set = array();
            while($row = $stmt->fetch())
            {
                $set[] = $row;
            }
            $res['result'] = $set;
        }catch(PDOException $e)
        {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }
    public function get_subject_by_id($subject_id)
    {
        $res = array();
        $res['status'] = 1;
        try{
            $sql = "SELECT * FROM subjects WHERE id=:s_id";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':s_id', $subject_id, PDO::PARAM_INT);
            $stmt->execute();
            $res['result'] = $stmt->fetch();
        }catch(PDOException $e)
        {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }
    public function get_user_by_id($user_id)
    {
        $res = array();
        $res['status'] = 1;
        try{
            $sql = "SELECT * FROM users WHERE id=:g_id";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':g_id', $user_id, PDO::PARAM_INT);
            $stmt->execute();
            $res['result'] = $stmt->fetch();
        }catch(PDOException $e)
        {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }
    public function get_user_by_user_name($user_name)
    {
        $res = array();
        $res['status'] = 1;
        try{
            $sql = "SELECT * FROM users WHERE user_name=:u_name";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':u_name', $user_name, PDO::PARAM_INT);
            $stmt->execute();
            $res['result'] = $stmt->fetch();
        }catch(PDOException $e)
        {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }
    public function add_user($full_name,$user_name,$password,$type,$privileges)
    {
        $res = array();
        $res['status'] = 1;
        try {
            $sql = "INSERT INTO users(full_name,user_name,user_pass,user_type,privilage) VALUE (:f_name,:u_name,password(:pass),:type,:priv)";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':f_name', $full_name, PDO::PARAM_STR);
            $stmt->bindvalue(':u_name', $user_name, PDO::PARAM_STR);
            $stmt->bindvalue(':pass', $password, PDO::PARAM_STR);
            $stmt->bindvalue(':type', $type, PDO::PARAM_STR);
            $stmt->bindvalue(':priv', $privileges, PDO::PARAM_STR);
            $stmt->execute();
        } catch (PDOException $e) {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }
    public function update_user_type($id, $type)
    {
        $res = array();
        $res['status'] = 1;
        try {
            $sql = "UPDATE users SET user_type=:us_id WHERE id=:id";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':id', $id, PDO::PARAM_INT);
            $stmt->bindvalue(':us_id', $type, PDO::PARAM_STR);
            $stmt->execute();
        } catch (PDOException $e) {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }
    public function remove_user($user_id)
    {
        $res = array();
        $res['status'] = 1;
        try {
            $sql = "DELETE FROM users WHERE id=:s_id";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':s_id', $user_id, PDO::PARAM_INT);
            $stmt->execute();
        } catch (PDOException $e) {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }
    public function update_user_privilege($id, $privilege)
    {
        $res = array();
        $res['status'] = 1;
        try {
            $sql = "UPDATE users SET privilage=:us_id WHERE id=:id";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':id', $id, PDO::PARAM_INT);
            $stmt->bindvalue(':us_id', $privilege, PDO::PARAM_STR);
            $stmt->execute();
        } catch (PDOException $e) {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }
    public function get_users()
    {
        $res = array();
        $res['status'] = 1;
        try{
            $sql = "SELECT * FROM users ORDER BY privilage";
            $stmt = $this->connection->prepare($sql);
            $stmt->execute();
            $res['result'] = array();
            $set = array();
            while($row = $stmt->fetch())
            {
                $set[] = $row;
            }
            $res['result'] = $set;
        }catch(PDOException $e)
        {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }
    public function is_valid_user($user_name,$user_pass){
        $res = array();
        $res['status'] = 1;
        try{
            $sql = "SELECT * FROM users WHERE user_pass=password(:pass) AND user_name=:u_name";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':u_name', $user_name, PDO::PARAM_STR);
            $stmt->bindvalue(':pass', $user_pass, PDO::PARAM_STR);
            $stmt->execute();
            $res['result'] = $stmt->fetch();
        }catch(PDOException $e)
        {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }
    public function remove_subject($subject_id)
    {
        $res = array();
        $res['status'] = 1;
        try {
            $sql = "DELETE FROM subjects WHERE id=:s_id";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':s_id', $subject_id, PDO::PARAM_INT);
            $stmt->execute();
        } catch (PDOException $e) {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }
    public function remove_grade($grade_id)
    {
        $res = array();
        $res['status'] = 1;
        try {
            $sql = "DELETE FROM grades WHERE id=:s_id";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':s_id', $grade_id, PDO::PARAM_INT);
            $stmt->execute();
        } catch (PDOException $e) {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }
} 