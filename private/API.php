<?php
/**
 * Created by PhpStorm.
 * User: Robel
 * Date: 4/2/2015
 * Time: 4:09 AM
 */
/*
 *
 * this file contains function(Methods) that allows us to perform different kinds of data transactions
 *  between the front end of the system(Android APP + Web site) and back end of the system(Database)
 *
 * this is the interface where the front and back ends of the system interact.
*/


/*
 *
 * every method in this script returns an array containing at least a status flag.
 * the status flag can be accessed using the array index 'status': 1 means attempt succeeded and the retrieved data(if any)
 *                                                                      can be accessed under array index 'result'
 *                                                                 0 means attempt failed and an error report comes
 *                                                                      along with it under array index 'report'
 *
 *      example: $returned = any_function_call();
 *      check if attempt was successful => if($returned['status'] == 0) // failed
 *                                              {
 *                                                  if(isset($returned['report'])// just to be safe
 *                                                      {
 *                                                          $error_report = $returned['report']
 *                                                      }
 *                                               }
 *                                          else if($returned['status'] == 1) // successful
 *                                              {
 *                                                  we can receive the returned data (if any) as
 *                                                  if(isset($returned['result'])  //  again, just to be safe
 *                                                      $received = $returned['result']
 *                                              }
 */

require_once "DB_Access.php";
require_once($_SERVER['DOCUMENT_ROOT']."/Alekas/private/Classes/PHPExcel.php");
//$work_book = new PHPExcel();
class API
{
    private $connection;
    private $database;

    public function __construct()
    {
        $this->database = new DB_Access();
        $this->connection = $this->database->pass_connection();
        $this->connection->query("USE content_manager"); // datebase selection
    }



    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //                      ADD functions (set methods)
    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    public function add_admin($fullname, $uname, $pass, $privilage_id, $rep_entity, $scope_id)
    {
        $res = array();
        $res['status'] = 1;
        try {
            $sql = "INSERT INTO admins(fullname, username, password, privilage_id, representedentity, scopeID) VALUES(:fname, :uname, password(:pass), :pri_id, :rep, :sco_id)";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':fname', $fullname, PDO::PARAM_STR);
            $stmt->bindvalue(':uname', $uname, PDO::PARAM_STR);
            $stmt->bindvalue(':pass', $pass, PDO::PARAM_STR);
            $stmt->bindvalue(':pri_id', $privilage_id, PDO::PARAM_INT);
            $stmt->bindvalue(':rep', $rep_entity, PDO::PARAM_STR);
            $stmt->bindvalue(':sco_id', $scope_id, PDO::PARAM_INT);
            $stmt->execute();
        } catch (PDOException $e) {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }

    public function add_assignment($AssignmentName, $lect_id, $coarse_id, $class_id, $file, $sub_date, $description)
    {
        // add a new assignment to the database
        // this table helps keep track of the assignments posted by teachers to the students...
        //

        $res = array();
        $res['status'] = 1;
        try {
            $sql = "INSERT INTO assignments(assignments.name, lectID, coarseID, classID, filepath, submissionDate, description) VALUES (:nam,:lect_id, :coa_id, :cls_id, :file, :sub_date, :description)";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':nam', $AssignmentName, PDO::PARAM_STR);
            $stmt->bindvalue(':lect_id', $lect_id, PDO::PARAM_INT);
            $stmt->bindvalue(':coa_id', $coarse_id, PDO::PARAM_INT);
            $stmt->bindvalue(':cls_id', $class_id, PDO::PARAM_INT);
            $stmt->bindvalue(':file', $file, PDO::PARAM_STR);
            $stmt->bindvalue(':sub_date', $sub_date, PDO::PARAM_STR);
            $stmt->bindvalue(':description', $description, PDO::PARAM_STR);
            $stmt->execute();
        } catch (PDOException $e) {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }

    public function add_class($dep_id, $year, $stream = null, $section = null, $program_id)
    {
        // this function inserts new rows in to the table classes.
        // the relation classes is a list of all the meaningful combinations of all years, departments,
        // sections, programs(as undergraduate and post graduate) in the school...
        // we refer to this combination as class and use the id at which the tupple is located in the table as class_id
        // this table is a meta data
        $res = array();
        $res['status'] = 1;
        try {
            $sql = "INSERT INTO classes(department_id, classes.year, stream_id, section_id, program_id) VALUES(:dept_id, :yr, :str, :sec, :prog)";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':dept_id', $dep_id, PDO::PARAM_INT);
            $stmt->bindvalue(':yr', $year, PDO::PARAM_INT);
            $stmt->bindvalue(':str', $stream, PDO::PARAM_INT);
            $stmt->bindvalue(':sec', $section, PDO::PARAM_INT);
            $stmt->bindvalue(':prog', $program_id, PDO::PARAM_INT);
            $stmt->execute();
        } catch (PDOException $e) {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }

    public function add_coarse($co_name, $co_code)
    {
        // table coarses holds the list of all coarses provided in the school... freshman to postgraduate
        // this function inserts new entries
        // meta data
        $res = array();
        $res['status'] = 1;
        try {
            $sql = "INSERT INTO coarses(coarseName, coarseCode) VALUES(:c_name, :c_code)";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':c_name', $co_name, PDO::PARAM_STR);
            $stmt->bindvalue(':c_code', $co_code, PDO::PARAM_STR);
            $stmt->execute();
        } catch (PDOException $e) {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }

    public function add_department($name)
    {
        // the table departments holds list of all the departments in the school
        // this function inserts new departments to te table
        // meta data
        $res = array();
        $res['status'] = 1;
        try {
            $sql = "INSERT INTO departments(name) VALUE (:name)";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':name', $name, PDO::PARAM_STR);
            $stmt->execute();

        } catch (PDOException $e) {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }

    public function add_lecturer($name, $phone, $office_number, $staff_code)
    {
        // tin this relation, staff code is a verification code used to authenticate mob user teachers
        // during registration, the teacher receives a verification code from the system administrator or
        // school notification administrator and enters that code in the registration form to proceed as a previlaged user
        //meta data
        $res = array();
        $res['status'] = 1;
        try {
            $sql = "INSERT INTO lecturers(FullName, phone, officeNumber, staffCode) VALUES(:fname, :phone, :office_num, :staff_code)";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':fname', $name, PDO::PARAM_STR);
            $stmt->bindvalue(':phone', $phone, PDO::PARAM_STR);
            $stmt->bindvalue(':office_num', $office_number, PDO::PARAM_STR);
            $stmt->bindvalue(':staff_code', $staff_code, PDO::PARAM_STR);
            $stmt->execute();
        } catch (PDOException $e) {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }
    //edited
    //to update if it cant insert
    public function add_lecturer_status($lect_id, $status, $status, $StartTime, $EndTime)
    {
        // this table holds statuses of the lecturers...
        // when a teacher cancels a class a new entry to this table is added holding the teachers id and the timeof the
        // cancelled time( start_time - class start time, end_time - class end time)
        // when a teacher wants to infrom his students that he is available at his office during contact hours
        // a  new entry is added to the table (with start_time and end_time of the consulting hours)
        $ret = array();
        $ret['status'] = 1;
        $stt = time() + ($StartTime * 60);
        $ends = $stt + ($EndTime * 60);
        $start_time = date("Y-m-d h:i:s", $stt);
        $end_time = date("Y-m-d h:i:s", $ends);
        $modified = date("Y-m-d h:i:s", time());
        try {
            $sql = "INSERT INTO lecturerstatuses(lectID, status, starttime, endtime) VALUES(:lect_id, :status, :start, :end_time)";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':lect_id', $lect_id, PDO::PARAM_INT);
            $stmt->bindvalue(':status', $status, PDO::PARAM_STR);
            $stmt->bindvalue(':start', $start_time, PDO::PARAM_STR);
            $stmt->bindvalue(':end_time', $end_time, PDO::PARAM_STR);
            $stmt->execute();
        } catch (PDOException $e) {
            if ($e->getMessage() == "")
                try {
                    $sql = "UPDATE lecturerstatuses SET status=:lc_stat, starttime=:lc_st, endtime=:lc_en, modified=:modi WHERE lectID=$lect_id";
                    $stmt = $this->connection->prepare($sql);
                    $stmt->bindvalue(':lc_stat', $status, PDO::PARAM_STR);
                    $stmt->bindvalue(':lc_st', $start_time, PDO::PARAM_STR);
                    $stmt->bindvalue(':lc_en', $end_time, PDO::PARAM_STR);
                    $stmt->bindvalue(':modi', $modified, PDO::PARAM_INT);
                    $stmt->execute();
                } catch (PDOException $e) {
                    $res['status'] = 0;
                    $res['report'] = $e->getMessage();
                }
        }
        return $ret;
    }

    public function get_class_status($lect_id, $class_id, $coarse_id, $start_time, $day)
    {
        $res = array();
        $res['status'] = 1;
        try {
            // $sql = "SELECT status FROM schedules WHERE lecturerID=:lect_id AND classID=:cls_id AND coarseID=:co_id AND startTime=:st_tim AND schedules.day=:da";
            $sql = "SELECT status FROM schedules WHERE lecturerID=:lect_id AND classID=:cls_id AND coarseID=:co_id AND startTime=:st_tim AND schedules.day=:da";

            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':lect_id', $lect_id, PDO::PARAM_INT);
            $stmt->bindvalue(':cls_id', $class_id, PDO::PARAM_INT);
            $stmt->bindvalue(':co_id', $coarse_id, PDO::PARAM_INT);
            $stmt->bindvalue(':st_tim', $start_time, PDO::PARAM_STR);
            $stmt->bindvalue(':da', $day, PDO::PARAM_STR);
            $stmt->execute();
            $res['result'] = $stmt->fetch();
        } catch (PDOException $e) {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }

    public function change_class_status($lect_id, $class_id, $coarse_id, $start_Time, $day)
    {
        $res = array();
        $res['status'] = 1;
        try {
            $response = $this->get_class_status($lect_id, $class_id, $coarse_id, $start_Time, $day);
            if (isset($response['status']) and $response['status'] == 1) {
                $resp = $response['result']['status'];
                if ($resp == 1) {
                    $new_stat = 0;
                    $sql1 = "UPDATE schedules SET status=:val WHERE lecturerID=:lect_id AND classID=:cls_id AND coarseID=:co_id AND startTime=:st_tim AND schedules.day=:da";
                } else {
                    $new_stat = 1;
                    $sql1 = "UPDATE schedules SET status=:val WHERE lecturerID=:lect_id AND classID=:cls_id AND coarseID=:co_id AND startTime=:st_tim AND schedules.day=:da";
                }

                $stmt = $this->connection->prepare($sql1);
                $stmt->bindvalue(':lect_id', $lect_id, PDO::PARAM_INT);
                $stmt->bindvalue(':val', $new_stat, PDO::PARAM_INT);
                $stmt->bindvalue(':cls_id', $class_id, PDO::PARAM_INT);
                $stmt->bindvalue(':co_id', $coarse_id, PDO::PARAM_INT);
                $stmt->bindvalue(':st_tim', $start_Time, PDO::PARAM_STR);
                $stmt->bindvalue(':da', $day, PDO::PARAM_STR);
                //$stmt->execute();
                $result = $stmt->execute();
                if (!$result) {
                    throw new PDOException("failed to toggle status");
                }
                $res['result'] = $new_stat;
            } else {
                throw new PDOException($response['report']);
            }

        } catch (PDOException $e) {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }

    public function add_new_User($IMEI, $UserName, $Password)
    {
        // this table holds a set of frequently used information about a mobile app user
        $res = array();
        $res['status'] = 1;
        try {
            $sql = "INSERT INTO users(IMEI, userName, password) VALUES (:imei, :username, password(:pass))";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':imei', $IMEI, PDO::PARAM_STR);
            $stmt->bindvalue(':username', $UserName, PDO::PARAM_STR);
            $stmt->bindvalue(':pass', $Password, PDO::PARAM_STR);
            $stmt->execute();
        } catch (PDOException $e) {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }

    public function add_new_user_profile($mobuser_id, $fullname, $email, $phone, $class_id, $photo_url, $school_id)
    {
        // this function registers a mobile users profile in the mob usser table(relation)...
        // the mobuser_id references the id at which the users mobile device imei code is registered at
        // photo url links the photo of the user stored in the specified storage for user profile pictures
        //(Aleka/private/user_files/photos(all))
        $res = array();
        $res['status'] = 1;
        try {
            $sql = "INSERT INTO userprofiles(MobUserID, FullName, Email, phone, classID, photo, SchoolID) VALUES(:uid, :fname, :email, :phone, :cid, :photo, :sid)";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':uid', $mobuser_id, PDO::PARAM_INT);
            $stmt->bindvalue(':fname', $fullname, PDO::PARAM_STR);
            $stmt->bindvalue(':email', $email, PDO::PARAM_STR);
            $stmt->bindvalue(':phone', $phone, PDO::PARAM_STR);
            $stmt->bindvalue(':cid', $class_id, PDO::PARAM_INT);
            $stmt->bindvalue(':photo', $photo_url, PDO::PARAM_STR);
            $stmt->bindvalue(':sid', $school_id, PDO::PARAM_STR);
            $stmt->execute();
        } catch (PDOException $e) {
            try {
                $sql = "UPDATE userprofiles SET FullName=:fname, Email=:email, classID=:cid, photo=:photo WHERE MobUserID=$mobuser_id";
                $stmt = $this->connection->prepare($sql);
                $stmt->bindvalue(':fname', $fullname, PDO::PARAM_STR);
                $stmt->bindvalue(':email', $email, PDO::PARAM_STR);
                $stmt->bindvalue(':cid', $class_id, PDO::PARAM_INT);
                $stmt->bindvalue(':photo', $photo_url, PDO::PARAM_STR);
                $stmt->execute();
            } catch (PDOException $e) {
                $res['status'] = 0;
                $res['report'] = $e->getMessage();
            }

        }
        return $res;
    }

    public function add_new_user_profile_with_imei($IMEI, $fullname, $email, $phone, $class_id, $photo_url, $school_id)
    {
        // this function registers a mobile users profile in the mob user table(relation)...
        // the mobuser_id references the id at which the users mobile device imei code is registered at
        // photo url links the photo of the user stored in the specified storage for user profile pictures
        //(Aleka/private/user_files/photos(all))
        $mobuser_id = $this->get_user_id($IMEI)['result']['id'];
        $res = array();
        $res['status'] = 1;
        try {
            $sql = "INSERT INTO userprofiles(MobUserID, FullName, Email, phone, classID, photo, SchoolID) VALUES(:uid, :fname, :email, :phone, :cid, :photo, :sid)";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':uid', $mobuser_id, PDO::PARAM_INT);
            $stmt->bindvalue(':fname', $fullname, PDO::PARAM_STR);
            $stmt->bindvalue(':email', $email, PDO::PARAM_STR);
            $stmt->bindvalue(':phone', $phone, PDO::PARAM_STR);
            $stmt->bindvalue(':cid', $class_id, PDO::PARAM_INT);
            $stmt->bindvalue(':photo', $photo_url, PDO::PARAM_STR);
            $stmt->bindvalue(':sid', $school_id, PDO::PARAM_STR);
            $stmt->execute();
        } catch (PDOException $e) {
            try {
                $sql = "UPDATE userprofiles SET FullName=:fname, Email=:email, classID=:cid, photo=:photo WHERE MobUserID=$mobuser_id";
                $stmt = $this->connection->prepare($sql);
                $stmt->bindvalue(':fname', $fullname, PDO::PARAM_STR);
                $stmt->bindvalue(':email', $email, PDO::PARAM_STR);
                $stmt->bindvalue(':cid', $class_id, PDO::PARAM_INT);
                $stmt->bindvalue(':photo', $photo_url, PDO::PARAM_STR);
                $stmt->execute();
            } catch (PDOException $e) {
                $res['status'] = 0;
                $res['report'] = $e->getMessage();
            }
        }
        return $res;
    }

    public function add_notification($user_id, $class_id, $sender, $title, $body, $start_time, $end_time, $category)
    {
        // this table holds all the posted notifications along with which class they refer to
        $res = array();
        $res['status'] = 1;
        try {
            $sql = "INSERT INTO notifications(userID,classID, sender, title, body, starttime, endtime,category) VALUES(:us_id,:cl_id, :sender, :title, :body, :st_time, :end_time,:cat_id)";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':us_id', $user_id, PDO::PARAM_INT);
            $stmt->bindvalue(':cl_id', $class_id, PDO::PARAM_INT);
            $stmt->bindvalue(':sender', $sender, PDO::PARAM_STR);
            $stmt->bindvalue(':title', $title, PDO::PARAM_STR);
            $stmt->bindvalue(':body', $body, PDO::PARAM_STR);
            $stmt->bindvalue(':st_time', $start_time, PDO::PARAM_STR);
            $stmt->bindvalue(':end_time', $end_time, PDO::PARAM_STR);
            $stmt->bindvalue(':cat_id', $category, PDO::PARAM_STR);
            $stmt->execute();
            $r = $this->Send_SMS_By_Class($class_id, $title." : ".$body);
            if(isset($r['status']) AND $r['status'] == 1)
            {

            }else{
                throw new PDOException($r['report']);
            }
        } catch (PDOException $e) {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }

    public function add_noti_log($noti_id, $mob_user_id)
    {
        $res = array();
        $res['status'] = 1;
        try {
            $sql = "INSERT INTO notilogs(notiID, mobUserID) VALUES(:noti_id, :user_id)";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':noti_id', $noti_id, PDO::PARAM_INT);
            $stmt->bindvalue(':user_id', $mob_user_id, PDO::PARAM_INT);
            $stmt->execute();
        } catch (PDOException $e) {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }

    public function add_privilages($name)
    {
        $res = array();
        $res['status'] = 1;
        try {
            $sql = "INSERT INTO privilages(name) VALUE(:p_name)";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':p_name', $name, PDO::PARAM_STR);
            $stmt->execute();
        } catch (PDOException $e) {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }

    public function add_result_report($lect_id, $coarse_id, $class_id, $filename, $description)
    {
        $res = array();
        $res['status'] = 1;
        try {
            $sql = "INSERT INTO resultreports(lectID, coarseID, classID, filename, description) VALUES(:l_id, :co_id, :cl_id, :fname, :des)";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':l_id', $lect_id, PDO::PARAM_INT);
            $stmt->bindvalue(':co_id', $coarse_id, PDO::PARAM_INT);
            $stmt->bindvalue(':cl_id', $class_id, PDO::PARAM_INT);
            $stmt->bindvalue(':fname', $filename, PDO::PARAM_STR);
            $stmt->bindvalue(':des', $description, PDO::PARAM_STR);
            $stmt->execute();
        } catch (PDOException $e) {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }

    public function add_schedule($class_id, $coarse_id, $start_time, $end_time, $class_room, $day, $lect_id)
    {
        //edited
        $res = array();
        $res['status'] = 1;
        try {
            $sql = "INSERT INTO schedules(classID, coarseID, startTime, endTime,classRoom, schedules.day, lecturerID) VALUES(:cl_id, :co_id, :st_time, :en_time,:cl_rm, :da, :le_id)";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':cl_id', $class_id, PDO::PARAM_INT);
            $stmt->bindvalue(':co_id', $coarse_id, PDO::PARAM_INT);
            $stmt->bindvalue(':st_time', $start_time, PDO::PARAM_STR);
            $stmt->bindvalue(':en_time', $end_time, PDO::PARAM_STR);
            $stmt->bindvalue(':cl_rm', $class_room, PDO::PARAM_STR);
            $stmt->bindvalue(':da', $day, PDO::PARAM_STR);
            $stmt->bindvalue(':le_id', $lect_id, PDO::PARAM_INT);
            $stmt->execute();
        } catch (PDOException $e) {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }

    public function add_scope($name)
    {
        $res = array();
        $res['status'] = 1;
        try {
            $sql = "INSERT INTO scopes(name) VALUE(:scope_name)";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':scope_name', $name, PDO::PARAM_STR);
            $stmt->execute();
        } catch (PDOException $e) {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }

    public function add_stream($dept_id, $name)
    {
        $res = array();
        $res['status'] = 1;
        try {
            $sql = "INSERT INTO streams(name, dept_ID) VALUES(:st_name, :dept_id)";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':st_name', $name, PDO::PARAM_STR);
            $stmt->bindvalue(':dept_id', $dept_id, PDO::PARAM_INT);
            $stmt->execute();
        } catch (PDOException $e) {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }

    public function add_section($name, $dept_id, $year, $stream)
    {
        $res = array();
        $res['status'] = 1;
        try {
            $sql = "INSERT INTO sections(sections.name, dept_ID, sections.year, stream_id) VALUES(:sec_name, :dept_id, :sec_year, :stream_id)";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':sec_name', $name, PDO::PARAM_STR);
            $stmt->bindvalue(':dept_id', $dept_id, PDO::PARAM_INT);
            $stmt->bindvalue(':sec_year', $year, PDO::PARAM_INT);
            $stmt->bindvalue(':stream_id', $stream, PDO::PARAM_INT);
            $stmt->execute();
        } catch (PDOException $e) {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }

    public function add_program($name)
    {
        $res = array();
        $res['status'] = 1;
        try {
            $sql = "INSERT INTO programs(programs.name) VALUE(:pro_name)";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':pro_name', $name, PDO::PARAM_STR);
            $stmt->execute();
        } catch (PDOException $e) {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }




    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //                      GET-ALL functions (get methods returning every thing) - no filters
    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    public function get_all_admins()
    {
        $res = array();
        $res['status'] = 1;
        try {
            $sql = "SELECT * FROM admins ORDER  BY id";
            $stmt = $this->connection->prepare($sql);
            $stmt->execute();
            $set = array();
            while ($row = $stmt->fetch()) {
                $set[] = $row;
            }
            $res['result'] = $set;
        } catch (PDOException $e) {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }

    public function get_all_programs()
    {
        $res = array();
        $res['status'] = 1;
        try {
            $sql = "SELECT * FROM programs ORDER  BY id";
            $stmt = $this->connection->prepare($sql);
            $stmt->execute();
            $set = array();
            while ($row = $stmt->fetch()) {
                $set[] = $row;
            }
            $res['result'] = $set;
        } catch (PDOException $e) {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }

    public function get_all_assignments()
    {
        $res = array();
        $res['status'] = 1;
        try {
            $sql = "SELECT * FROM assignments ORDER BY id";
            $stmt = $this->connection->prepare($sql);
            $stmt->execute();
            $set = array();
            while ($row = $stmt->fetch()) {
                $set[] = $row;
            }
            $res['result'] = $set;
        } catch (PDOException $e) {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }

    public function get_all_classes()
    {
        $res = array();
        $res['status'] = 1;
        try {
            $sql = "SELECT * FROM classes ORDER BY id";
            $stmt = $this->connection->prepare($sql);
            $stmt->execute();
            $set = array();
            while ($row = $stmt->fetch()) {
                $set[] = $row;
            }
            $res['result'] = $set;
        } catch (PDOException $e) {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }

    public function get_all_coarses()
    {
        $res = array();
        $res['status'] = 1;
        try {
            $sql = "SELECT * FROM coarses ORDER BY id";
            $stmt = $this->connection->prepare($sql);
            $stmt->execute();
            $set = array();
            while ($row = $stmt->fetch()) {
                $set[] = $row;
            }
            $res['result'] = $set;
        } catch (PDOException $e) {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }

    public function get_all_departments()
    {
        $res = array();
        $res['status'] = 1;
        try {
            $sql = "SELECT departments.id, departments.name FROM departments ORDER BY departments.id";
            $stmt = $this->connection->prepare($sql);
            $stmt->execute();
            $set = array();
            while ($row = $stmt->fetch()) {
                $set[] = $row;
            }
            $res['result'] = $set;
        } catch (PDOException $e) {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }

    public function get_all_stream()
    {
        $res = array();
        $res['status'] = 1;
        try {
            $sql = "SELECT * FROM streams ORDER  BY id";
            $stmt = $this->connection->prepare($sql);
            $stmt->execute();
            $set = array();
            while ($row = $stmt->fetch()) {
                $set[] = $row;
            }
            $res['result'] = $set;
        } catch (PDOException $e) {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }

        return $res;
    }

    public function get_all_sections()
    {
        $res = array();
        $res['status'] = 1;
        try {
            $sql = "SELECT * FROM sections ORDER  BY id";
            $stmt = $this->connection->prepare($sql);
            $stmt->execute();
            $set = array();
            while ($row = $stmt->fetch()) {
                $set[] = $row;
            }
            $res['result'] = $set;
        } catch (PDOException $e) {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }

        return $res;
    }

    public function get_all_lecturers()
    {
        $res = array();
        $res['status'] = 1;
        try {
            $sql = "SELECT id, FullName, phone, officeNumber, staffCode FROM lecturers ORDER BY id";
            $stmt = $this->connection->prepare($sql);
            $stmt->execute();
            $set = array();
            while ($row = $stmt->fetch()) {
                $set[] = $row;
            }
            $res['result'] = $set;
        } catch (PDOException $e) {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }

    public function get_all_lecturer_statuses()
    {
        $res = array();
        $res['status'] = 1;
        try {
            $sql = "SELECT * FROM lecturerstatuses ORDER BY id";
            $stmt = $this->connection->prepare($sql);
            $stmt->execute();
            $set = array();
            while ($row = $stmt->fetch()) {
                $set[] = $row;
            }
            $res['result'] = $set;
        } catch (PDOException $e) {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }

    public function get_all_users()
    {
        $res = array();
        $res['status'] = 1;
        try {
            $sql = "SELECT * FROM users ORDER BY id";
            $stmt = $this->connection->prepare($sql);
            $stmt->execute();
            $set = array();
            while ($row = $stmt->fetch()) {
                $set[] = $row;
            }
            $res['result'] = $set;
        } catch (PDOException $e) {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }

    public function get_all_user_profiles()
    {
        $res = array();
        $res['status'] = 1;
        try {
            $sql = "SELECT * FROM userprofiles ORDER BY id";
            $stmt = $this->connection->prepare($sql);
            $stmt->execute();
            $set = array();
            while ($row = $stmt->fetch()) {
                $set[] = $row;
            }
            $res['result'] = $set;
        } catch (PDOException $e) {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }

    public function get_all_notifications()
    {
        $res = array();
        $res['status'] = 1;
        try {
            $sql = "SELECT * FROM notifications ORDER BY id";
            $stmt = $this->connection->prepare($sql);
            $stmt->execute();
            $set = array();
            while ($row = $stmt->fetch()) {
                $set[] = $row;
            }
            $res['result'] = $set;
        } catch (PDOException $e) {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }

    public function get_all_noti_logs()
    {
        $res = array();
        $res['status'] = 1;
        try {
            $sql = "SELECT * FROM notilogs ORDER BY id";
            $stmt = $this->connection->prepare($sql);
            $stmt->execute();
            $set = array();
            while ($row = $stmt->fetch()) {
                $set[] = $row;
            }
            $res['result'] = $set;
        } catch (PDOException $e) {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }

    public function get_all_privilages()
    {
        $res = array();
        $res['status'] = 1;
        try {
            $sql = "SELECT * FROM privilages ORDER BY id";
            $stmt = $this->connection->prepare($sql);
            $stmt->execute();
            $set = array();
            while ($row = $stmt->fetch()) {
                $set[] = $row;
            }
            $res['result'] = $set;
        } catch (PDOException $e) {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }

    public function get_all_result_reports()
    {
        $res = array();
        $res['status'] = 1;
        try {
            $sql = "SELECT * FROM resultreports ORDER BY id";
            $stmt = $this->connection->prepare($sql);
            $stmt->execute();
            $set = array();
            while ($row = $stmt->fetch()) {
                $set[] = $row;
            }
            $res['result'] = $set;
        } catch (PDOException $e) {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }

    public function get_all_schedules_sort_id()
    {
        $res = array();
        $res['status'] = 1;
        try {
            $sql = "SELECT * FROM schedules ORDER BY id";
            $stmt = $this->connection->prepare($sql);
            $stmt->execute();
            $set = array();
            while ($row = $stmt->fetch()) {
                $set[] = $row;
            }
            $res['result'] = $set;
        } catch (PDOException $e) {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }

    public function get_all_schedules_group_by_class()
    {
        $res = array();
        $res['status'] = 1;
        try {
            $sql = "SELECT * FROM schedules GROUP BY classID ORDER BY  classID";
            $stmt = $this->connection->prepare($sql);
            $stmt->execute();
            $set = array();
            while ($row = $stmt->fetch()) {
                $set[] = $row;
            }
            $res['result'] = $set;
        } catch (PDOException $e) {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }

    //new
    public function get_schedules_by_classID($class_id)
    {
        $res = array();
        $res['status'] = 1;
        try {
            $sql = "SELECT * FROM schedules WHERE classID=:cl_id";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':cl_id', $class_id, PDO::PARAM_INT);
            $stmt->execute();
            $set = array();
            while ($row = $stmt->fetch()) {
                $set[] = $row;
            }
            $res['result'] = $set;
        } catch (PDOException $e) {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }

    public function get_all_scopes()
    {
        $res = array();
        $res['status'] = 1;
        try {
            $sql = "SELECT * FROM scopes ORDER BY id";
            $stmt = $this->connection->prepare($sql);
            $stmt->execute();
            $set = array();
            while ($row = $stmt->fetch()) {
                $set[] = $row;
            }
            $res['result'] = $set;
        } catch (PDOException $e) {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }

    public function get_all_streams_group_by_dept()
    {
        $res = array();
        $res['status'] = 1;
        try {
            $sql = "SELECT * FROM streams GROUP  BY deptID ORDER BY deptID";
            $stmt = $this->connection->prepare($sql);
            $stmt->execute();
            $set = array();
            while ($row = $stmt->fetch()) {
                $set[] = $row;
            }
            $res['result'] = $set;
        } catch (PDOException $e) {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }


    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //
    ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

    public function get_dept_name($dept_id)
    {
        $res = array();
        $res['status'] = 1;
        try {
            $sql = "SELECT name FROM departments WHERE id=:d_id";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':d_id', $dept_id, PDO::PARAM_INT);
            $stmt->execute();
            $res['result'] = $stmt->fetch();
        } catch (PDOException $e) {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }

    public function get_stream_name($stream_id)
    {
        $res = array();
        $res['status'] = 1;
        try {
            $sql = "SELECT name FROM streams WHERE id=:s_id";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':s_id', $stream_id, PDO::PARAM_INT);
            $stmt->execute();
            $res['result'] = $stmt->fetch();
        } catch (PDOException $e) {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }

    public function get_section_name($section_id)
    {
        $res = array();
        $res['status'] = 1;
        try {
            $sql = "SELECT name FROM sections WHERE id=:s_id";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':s_id', $section_id, PDO::PARAM_INT);
            $stmt->execute();
            $res['result'] = $stmt->fetch();
        } catch (PDOException $e) {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }

    public function get_program_name($program_id)
    {
        $res = array();
        $res['status'] = 1;
        try {
            $sql = "SELECT name FROM programs WHERE id=:p_id";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':p_id', $program_id, PDO::PARAM_INT);
            $stmt->execute();
            $res['result'] = $stmt->fetch();
        } catch (PDOException $e) {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }

    public function get_user_cred($IMEI)
    {

        // method retrieves username password combination for the given IMIE
        // identifies username by the registered mobile device IMIE code

        $res = array();
        $res['status'] = 1;
        try {
            $sql = "SELECT userName, password FROM users WHERE IMEI= :imei";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':imei', $IMEI, PDO::PARAM_STR);
            $stmt->execute();
            while ($row = $stmt->fetch()) {
                $res['result'][] = $row;
            }

        } catch (PDOException $e) {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }


    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    //
    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////


    public function get_class_id($dept_name, $section_name, $year, $stream_name, $program_name)
    {
        $res = array();
        $res['status'] = 1;
        try {
            $dept = $this->get_dept_id($dept_name);
            if (isset($dept['status']) and $dept['status'] == 1) {
                $dept_id = $dept['result']['id'];

            } else {
                throw new PDOException($dept['report']);
            }
            $stream = $this->get_stream_id($stream_name, $dept_id);
            if (isset($stream['status']) and $stream['status'] == 1) {
                $stream_id = $stream['result']['id'];

            } else {
                throw new PDOException($stream['report']);
            }
            $section = $this->get_section_id($section_name, $dept_id, $year, $stream_id);
            if (isset($section['status']) and $section['status'] == 1) {
                $section_id = $section['result']['id'];

            } else {
                throw new PDOException($section['report']);
            }
            $prog = $this->get_program_id($program_name);
            if (isset($prog['status']) and $prog['status'] == 1) {
                $prog_id = $prog['result']['id'];
            } else {
                throw new PDOException($prog['report']);
            }
            $sql = "SELECT id FROM classes WHERE department_id=:dept_id AND year=:yr AND stream_id=:str AND section_id=:sec AND program_id=:prog";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':dept_id', $dept_id, PDO::PARAM_INT);
            $stmt->bindvalue(':yr', $year, PDO::PARAM_INT);
            $stmt->bindvalue(':str', $stream_id, PDO::PARAM_INT);
            $stmt->bindvalue(':sec', $section_id, PDO::PARAM_INT);
            $stmt->bindvalue(':prog', $prog_id, PDO::PARAM_INT);
            $stmt->execute();
            $res['result'] = $stmt->fetch();
        } catch (PDOException $e) {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }

    public function get_program_id($prog_name)
    {
        $res = array();
        $res['status'] = 1;
        try {
            $sql = "SELECT  id FROM programs WHERE name=:name";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':name', $prog_name, PDO::PARAM_STR);
            $stmt->execute();
            $res['result'] = $stmt->fetch();
        } catch (PDOException $e) {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }

    public function get_stream_id($name, $dept_id)
    {
        $res = array();
        $res['status'] = 1;
        try {
            $sql = "SELECT id FROM streams WHERE name=:name AND dept_id=:dept_id";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':name', $name, PDO::PARAM_STR);
            $stmt->bindvalue(':dept_id', $dept_id, PDO::PARAM_INT);
            $stmt->execute();
            $res['result'] = $stmt->fetch();
        } catch (PDOException $e) {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }

    public function get_dept_id($name)
    {
        $res = array();
        $res['status'] = 1;
        try {
            $sql = "SELECT id FROM departments WHERE name=:name";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':name', $name, PDO::PARAM_STR);
            $stmt->execute();
            $res['result'] = $stmt->fetch();
        } catch (PDOException $e) {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }

    public function get_section_id($name, $dept_id, $year, $stream_id)
    {
        $res = array();
        $res['status'] = 1;
        try {
            $sql = "SELECT id FROM sections WHERE sections.name=:name AND dept_id=:dept_id AND sections.year=:yr AND stream_id=:str_id";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':name', $name, PDO::PARAM_STR);
            $stmt->bindvalue(':dept_id', $dept_id, PDO::PARAM_INT);
            $stmt->bindvalue(':yr', $year, PDO::PARAM_INT);
            $stmt->bindvalue(':str_id', $stream_id, PDO::PARAM_INT);
            $stmt->execute();
            $res['result'] = $stmt->fetch();
        } catch (PDOException $e) {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }

    public function IMEI_exists($IMEI)
    {
        $res = array();
        $res['status'] = 1;
        try {
            $sql = "SELECT * FROM userprofiles  WHERE imei=':imei'";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':imei', $IMEI, PDO::PARAM_STR);
            $stmt->execute();
            while ($row = $stmt->fetch_array()) {
                $res['result'][] = $row;
            }
        } catch (PDOException $e) {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }

    public function get_user_id($IMEI)
    {
        // this method retrieves the id to which user's mobile device is registered under to create a link between a registered
        // user in the mobuser table and other relations(tables) in the database like the user profile table
        // and notification log (noti_thankyou) tables...

        $res = array();
        $res['status'] = 1;
        try {
            $sql = "SELECT id FROM users WHERE IMEI= :imei";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':imei', $IMEI, PDO::PARAM_STR);
            $stmt->execute();
            $res['result'] = $stmt->fetch();
        } catch (PDOException $e) {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }

    ///Newly Added Codes
    /////////////////////////////////////////////////////////////////////////////////////////////////////////
    //********************************************************************************************************
    public function get_lecturer_name_by_id($id)
    {
        $res = array();
        $res['status'] = 1;
        try {
            $sql = "SELECT FullName FROM lecturers WHERE id= :lec_id";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':lec_id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $res['result'] = $stmt->fetch();
        } catch (PDOException $e) {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }

    public function get_lecturerID_by_staffCode($Staff_code)
    {
        $res = array();
        $res['status'] = 1;
        try {
            $sql = "SELECT id FROM lecturers WHERE staffCode= :stf_cd";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':stf_cd', $Staff_code, PDO::PARAM_INT);
            $stmt->execute();
            $res['result'] = $stmt->fetch();
        } catch (PDOException $e) {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }

    public function get_lecturer_status_by_id($lec_id)
    {
        $res = array();
        $res['status'] = 1;
        try {
            $sql = "SELECT * FROM schedules WHERE lecturerID= :l_id";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':l_id', $lec_id, PDO::PARAM_INT);
            $stmt->execute();
            while ($row = $stmt->fetch()) {
                $res['result'][] = $row;
            }
        } catch (PDOException $e) {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }

    public function get_lecturer_status_by_LecID($lec_id)
    {
        $res = array();
        $res['status'] = 1;
        try {
            $sql = "SELECT * FROM lecturerstatuses WHERE lectID= :lec_id";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':lec_id', $lec_id, PDO::PARAM_INT);
            $stmt->execute();
            $res['result'] = $stmt->fetch();
        } catch (PDOException $e) {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }

    public function get_course_name_by_id($id)
    {
        $res = array();
        $res['status'] = 1;
        try {
            $sql = "SELECT CoarseName FROM coarses WHERE id= :cr_id";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':cr_id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $res['result'] = $stmt->fetch();
        } catch (PDOException $e) {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }

    public function get_course_code_by_id($id)
    {
        $res = array();
        $res['status'] = 1;
        try {
            $sql = "SELECT coarseCode FROM coarses WHERE id= :cr_id";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':cr_id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $res['result'] = $stmt->fetch();
        } catch (PDOException $e) {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }

    public function get_all_schedule_by_classID($class_id)
    {
        $res = array();
        $res['status'] = 1;
        try {
            $sql = "SELECT * FROM schedules WHERE classID= :cl_id";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':cl_id', $class_id, PDO::PARAM_INT);
            $stmt->execute();
            $set = array();
            while ($row = $stmt->fetch()) {
                $set[] = $row;
            }
            $res['result'] = $set;
        } catch (PDOException $e) {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }

    public function is_user($IMEI)
    {
        $res = array();
        $res['status'] = 1;
        $res['result'] = 0;
        try {
            $sql = "SELECT * FROM users WHERE IMEI= :imei";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':imei', $IMEI, PDO::PARAM_STR);
            $stmt->execute();
            while ($row = $stmt->fetch()) {
                $res['result'] = 1;
            }

        } catch (PDOException $e) {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }

    public function get_classID_by_userID($user_id)
    {
        $res = array();
        $res['status'] = 1;
        try {
            $sql = "SELECT classID FROM userprofiles WHERE MobUserId= :user_id";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->execute();
            $res['result'] = $stmt->fetch();
        } catch (PDOException $e) {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }

    public function get_profile_by_userID($user_id)
    {
        $res = array();
        $res['status'] = 1;
        try {
            $sql = "SELECT * FROM userprofiles WHERE MobUserId= :user_id";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->execute();
            $res['result'] = $stmt->fetch();
        } catch (PDOException $e) {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }

    public function get_lectureres_classes($lect_id, $day)
    {
        $res = array();
        $res['status'] = 1;
        try {
            $sql = "SELECT * FROM schedules WHERE lecturerID=:lect_id";
            if ($day != null) {
                $sql .= " AND day=:day";
            }
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':lect_id', $lect_id, PDO::PARAM_INT);
            if ($day != null) {
                $stmt->bindvalue(':day', $day, PDO::PARAM_STR);
            }
            $stmt->execute();
            $data = array();
            while ($row = $stmt->fetch()) {
                $data[] = $row;
            }
            $res['result'] = $data;
        } catch (PDOException $e) {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }

    public function get_class_by_id($cls_id)
    {
        $res = array();
        $res['status'] = 1;
        try {
            $sql = "SELECT * FROM classes WHERE id=:cls_id";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':cls_id', $cls_id, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch();
            $res['result']['department'] = $this->get_dept_name($result['department_id'])['result'][0];
            $res['result']['year'] = $result['year'];
            $res['result']['stream'] = $this->get_stream_name($result['stream_id'])['result'][0];
            $res['result']['section'] = $this->get_section_name($result['section_id'])['result'][0];
            $res['result']['program'] = $this->get_program_name($result['program_id'])['result'][0];
        } catch (PDOException $e) {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }

    public function get_notifications_for_user_id($user_id)
    {
        $res = array();
        $res['status'] = 1;
        try {
            $class = $this->get_student_class_id($user_id);

            $class_id = null;
            if (isset($class['status']) AND $class['status'] == 1) {
                $class_id = $class['result'];
            } else {
                throw new PDOException($class['report']);
            }
//            echo "<br><br><br><br><br><br><br><br><br><br>";
            $sql = "SELECT * FROM notifications WHERE classID=:cls_id AND id NOT IN (SELECT notiId FROM notilogs WHERE mobUserID=:u_id)";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':cls_id', $class_id, PDO::PARAM_INT);
            $stmt->bindvalue(':u_id', $user_id, PDO::PARAM_INT);
            $result = $stmt->execute();
            $data = array();
            while ($row = $stmt->fetch()) {
                $data[] = $row;
            }
            $res['result'] = $data;
        } catch (PDOException $e) {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }

    public function get_all_notifications_for_user_id($user_id)
    {
        $res = array();
        $res['status'] = 1;
        try {
            $class = $this->get_student_class_id($user_id);

            $class_id = null;
            if (isset($class['status']) AND $class['status'] == 1) {
                $class_id = $class['result'];
            } else {
                throw new PDOException($class['report']);
            }
            //            echo "<br><br><br><br><br><br><br><br><br><br>";
            $sql = "SELECT * FROM notifications WHERE classID=:cls_id";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':cls_id', $class_id, PDO::PARAM_INT);
            $result = $stmt->execute();
            $data = array();
            while ($row = $stmt->fetch()) {
                $data[] = $row;
            }
            $res['result'] = $data;
        } catch (PDOException $e) {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }


    public function get_post($id)
    {
        $ret = array();
        $ret['status'] = 1;
        try {
            $sql = "SELECT * FROM posts WHERE id=:id";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $ret['result'] = $stmt->fetch();
        } catch (PDOException $e) {
            $ret['status'] = 0;
            $ret['report'] = $e->getMessage();
        }
        return $ret;
    }

    public function get_notifications_for_user($IMEI)
    {
        $res = array();
        $res['status'] = 1;
        try {
            $user_id = $this->get_user_id($IMEI)['result']['id'];
            $class_id = $this->get_classID_by_userID($user_id)['result']['classID'];
            $sql = "SELECT * FROM notifications WHERE classID=:cls_id AND id NOT IN (SELECT notiId FROM notilogs WHERE mobUserID=:u_id)";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':cls_id', $class_id, PDO::PARAM_INT);
            $stmt->bindvalue(':u_id', $user_id, PDO::PARAM_INT);
            $result = $stmt->execute();
            $data = array();
            while ($row = $stmt->fetch()) {
                $data[] = $row;
            }
            $res['result'] = $data;
        } catch (PDOException $e) {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }

    public function get_user($User_id)
    {
        $res = array();
        $res['status'] = 1;
        try {
            $sql = "SELECT * FROM users WHERE id =:id_";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':id_', $User_id, PDO::PARAM_INT);
            $result = $stmt->execute();
            $data = array();
            while ($row = $stmt->fetch()) {
                $res['result'] = $row;
            }


        } catch (PDOException $e) {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }

    public function get_coarse_id_by_coarse_name($name)
    {
        $res = array();
        $res['status'] = 1;
        try {
            $sql = "SELECT id FROM coarses WHERE coarseName=:c_name";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':c_name', $name, PDO::PARAM_STR);
            $stmt->execute();
            $res['result'] = $stmt->fetch();
        } catch (PDOException $e) {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }

    public function add_new_assignment($file_name, $Assignment_name, $staff_code, $coarse_name, $class_id, $sub_date, $description)
    {
        $res = array();
        $res['status'] = 1;
        try {
            $lect = $this->get_lecturerID_by_staffCode($staff_code);

            if (isset($lect['status']) AND $lect['status'] == 1) {
                $lect_id = $lect['result']['id'];
            } else {
                throw new PDOException($lect['report']);
            }
            $coarse = $this->get_coarse_id_by_coarse_name($coarse_name);

            if (isset($coarse['status']) AND $coarse['status'] == 1) {
                $coarse_id = $coarse['result']['id'];
            } else {
                throw new PDOException($coarse['report']);
            }
            $response = $this->add_assignment($Assignment_name, $lect_id, $coarse_id, $class_id, $file_name, $sub_date, $description);
            if (isset($response['status']) AND $response['status'] == 1) {
                $res['status'] = 1;
            } else {
                throw new PDOException($response['report']);
            }
        } catch (PDOException $e) {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }

    public function add_new_post($User_ID, $Ass_ID, $FileName)
    {
        $res = array();
        $res['status'] = 1;
        try {
            $sql = "INSERT INTO posts(userID, assignmentID, fileName) VALUES(:us_id,:as_id,:fl_nm)";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':us_id', $User_ID, PDO::PARAM_INT);
            $stmt->bindvalue(':as_id', $Ass_ID, PDO::PARAM_INT);
            $stmt->bindvalue(':fl_nm', $FileName, PDO::PARAM_STR);
            $stmt->execute();
        } catch (PDOException $e) {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }
    public function update_post_file($post_id, $FileName)
    {
        $res = array();
        $res['status'] = 1;
        try {
            $sql = "UPDATE posts SET fileName=:fl_nm WHERE id=:p_id";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':fl_nm', $FileName, PDO::PARAM_STR);
            $stmt->bindvalue(':p_id', $post_id, PDO::PARAM_INT);
            $stmt->execute();
            $res['result']= null;
        } catch (PDOException $e) {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }

    public function get_user_id_for_staff_code($staff_code)
    {
        $res = array();
        $res['status'] = 1;
        try {
            $sql = "SELECT user_id FROM lecturers WHERE staffcode=:stf_code";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':stf_code', $staff_code, PDO::PARAM_STR);
            $stmt->execute();
            while ($row = $stmt->fetch()) {
                $res['result'] = $row;
            }
        } catch (PDOException $e) {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }

    public function get_all_posts_by_user($user_id)
    {
        $res = array();
        $res['status'] = 1;
        try {
            $sql = "SELECT * FROM posts WHERE userID=:u_id";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':u_id', $user_id, PDO::PARAM_INT);
            $stmt->execute();
            $res['result'] = array();
            while ($row = $stmt->fetch()) {
                $res['result'][] = $row;
            }

        } catch (PDOException $e) {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }

    public function mark_post_as_seen($id)
    {
        $res = array();
        $res['status'] = 1;
        try {
            $sql = "UPDATE posts SET seen=1 WHERE id=:id";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
        } catch (PDOException $e) {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }

    public function get_all_posts_for_assignment($ass_id)
    {
        $res = array();
        $res['status'] = 1;
        try {
            $sql = "SELECT * FROM assignments WHERE assignmentID=:a_id";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':a_id', $ass_id, PDO::PARAM_INT);
            $stmt->execute();
            while ($row = $stmt->fetch()) {
                $res['result'][] = $row;
            }
        } catch (PDOException $e) {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }

    public function get_all_unseen_posts_for_assignment($ass_id)
    {
        $res = array();
        $res['status'] = 1;
        try {
            $sql = "SELECT * FROM posts WHERE assignmentID=:a_id AND seen=0";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':a_id', $ass_id, PDO::PARAM_INT);
            $stmt->execute();
            $res['result'] = array();
            while ($row = $stmt->fetch()) {
                $res['result'][] = $row;
            }
        } catch (PDOException $e) {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }

    //////////////////////////////////////////////////////////////////////////////////////////////////


    public function is_admin($user_name, $pass)
    {
        $res = array();
        $res['status'] = 1;
        $res['result'] = null;
        try {
            $sql = "SELECT id, username, privilage_id, representedentity FROM admins WHERE password=password(:pass)";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':pass', $pass, PDO::PARAM_STR);
            $stmt->execute();
            while ($row = $stmt->fetch()) {
                if ($row['username'] == $user_name) {
                    $res['result'] = $row;
                    break;
                }

            }
        } catch (PDOException $e) {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }

    public function get_user_school_id($user_id)
    {
        $res = array();
        $res['status'] = 1;
        try {
            $sql = "SELECT SchoolID FROM userprofiles WHERE MobUserID=:u_id";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':u_id', $user_id, PDO::PARAM_INT);
            $stmt->execute();
            $res['result'] = $stmt->fetch()['SchoolID'];
        } catch (PDOException $e) {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }

    public function get_student_class_id($user_id)
    {
        $res = array();
        $res['status'] = 1;
        try {
            $sql = "SELECT classID FROM userprofiles WHERE MobUserID=:u_id";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':u_id', $user_id, PDO::PARAM_INT);
            $stmt->execute();
            $res['result'] = $stmt->fetch()['classID'];
        } catch (PDOException $e) {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }

    public function get_report_file($class_id, $coarse_id)
    {
        $res = array();
        $res['status'] = 1;
        try {
            $sql = "SELECT filename FROM resultreports WHERE classID=:cls_id AND coarseID=:co_id";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':cls_id', $class_id, PDO::PARAM_INT);
            $stmt->bindvalue(':co_id', $coarse_id, PDO::PARAM_INT);
            $stmt->execute();
            $res['result'] = $stmt->fetch()['filename'];
        } catch (PDOException $e) {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }

    public function get_report_for_user($user_id, $coarse_id)
    {
        $res = array();
        $res['status'] = 1;

        try {
            $school_id = "";
            $class_id = 0;
            $file = "";
            $user_school_id = $this->get_user_school_id($user_id);
            if (isset($user_school_id['status']) AND $user_school_id['status'] == 1) {
                if (isset($user_school_id['result']) and $user_school_id['result'] != "")
                    $school_id = $user_school_id['result'];
                else
                    throw new PDOException("Opps...student id could not be fetched from database!");
            }
            $user_class_id = $this->get_student_class_id($user_id);
            if (isset($user_class_id['status']) AND $user_class_id['status'] == 1) {
                if (isset($user_class_id['result']) and $user_class_id['result'] != "")
                    $class_id = $user_class_id['result'];
                else
                    throw new PDOException("Opps...student class id could not be fetched from database!");
            }
            $file_name = $this->get_report_file($class_id, $coarse_id);

            if (isset($file_name['status']) AND $file_name['status'] == 1) {
                if (isset($file_name['result']) and $file_name['result'] != "")
                    $file = $file_name['result'];
                else
                    throw new PDOException("Opps...student class id could not be fetched from database!");
            }

            $response = $this->get_result_for_id($school_id, $file);
            if(isset($response['status']) AND $response['status'] == 1)
            {
                $res['report'] = $response['result'];
            }else{
                throw new PDOException($res['report']);
            }
        } catch (PDOException $e) {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }

    public function get_lecturers_class_ids($lect_id)
    {
        $res = array();
        $res['status'] = 1;
        try {
            $sql = "SELECT id, classID, coarseID FROM schedules WHERE lecturerID=:lect_id";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':lect_id', $lect_id, PDO::PARAM_INT);
            $stmt->execute();
            $sem = array();
            $res['result'] = null;
            while ($row = $stmt->fetch()) {
                $sem[] = $row;
                $res['result'] = $sem;
            }

        } catch (PDOException $e) {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }

    public function parse_name_for_class_I($class_id)
    {
        $res = array();
        $res['status'] = 1;
        try {

            $name = "";
            $detail = $this->get_class_by_id($class_id);
            $class = array();
            if (isset($detail['status']) AND $detail['status'] == 1) {
                $class = $detail['result'];
            } else {
                throw new Exception($detail['report']);
            }
            $program = $class['program'];
            $department = $class['department'];
            $year = $class['year'];
            $section = $class['section'];
            $stream = $class['stream'];

            if ($program == "Postgraduate") {
                $name .= "PG ";
                $name .= " - ";
            } elseif ($program == "Undergraduate") {
                $name .= "UG ";
                $name .= " - ";
            }


            if ($year == 1) {
                $name .= "1st year ";
            } elseif ($year == 2) {
                $name .= "2nd year ";
            } elseif ($year == 3) {
                $name .= "3rd year ";
            } elseif ($year == 4) {
                $name .= "4th year ";
            } elseif ($year == 5) {
                $name .= "5th year ";
            }

            $name .= " - ";

            if ($department != "") {
                $name .= $department;
            }
            $res['result'] = $name;
        } catch (Exception $e) {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }

    public function parse_name_for_class_II($class_id)
    {
        $res = array();
        $res['status'] = 1;
        try {
            $name = "";
            $detail = $this->get_class_by_id($class_id);
            $class = array();
            if (isset($detail['status']) AND $detail['status'] == 1) {
                $class = $detail['result'];
            } else {
                throw new Exception($detail['report']);
            }
            $year = $class['year'];
            $section = $class['section'];
            $stream = $class['stream'];
            if (isset($stream) AND $stream != "") {
                $name .= $stream;
                if (isset($section) AND $section != "") {
                    $name .= " - " . $section;
                }
            } elseif (isset($section) AND $section != "" AND isset($year) AND $year != "") {
                $name .= $year . "" . $section;
            }
            $res['result'] = $name;
        } catch (Exception $e) {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }

    public function get_lecturer_classes($lect_id)
    {
        $res = array();
        $res['status'] = 1;
        try {
            $class_names = null;
            $class_ids = null;
            $classes = $this->get_lecturers_class_ids($lect_id);
            if (isset($classes['status']) AND $classes['status'] == 1) {
                $class_ids = $classes['result'];
            } else {
                throw new PDOException($classes['report']);
            }
            if (is_array($class_ids)) {
                $found = array();
                foreach ($class_ids as $class) {
                    $class_id = $class['classID'];
                    $coarse_id = $class['coarseID'];
                    $found['class_id'] = $class_id;
                    $f = $this->parse_name_for_class_I($class_id);
                    if (isset($f['status']) AND $f['status'] == 1) {
                        $found['class_name_I'] = $f['result'];
                    } else {
                        throw new Exception($f['report']);
                    }
                    $f = $this->parse_name_for_class_II($class_id);
                    if (isset($f['status']) AND $f['status'] == 1) {
                        $found['class_name_II'] = $f['result'];
                    } else {
                        throw new Exception($f['report']);
                    }
                    $coarse = null;
                    $co = $this->get_coarse_name($coarse_id);
                    if (isset($co['status']) AND $co['status'] == 1) {
                        $coarse = $co['result'];
                    } else {
                        throw new Exception($f['report']);
                    }
                    $found['coarse_name'] = $coarse;
                    $res['result'][] = $found;
                }
            } elseif ($class_ids != null) {
                $class_id = $class_ids['classID'];
                $coarse_id = $class_ids['coarseID'];
                $found = array();
                $found['class_id'] = $class_id;
                $f = $this->parse_name_for_class_I($class_id);
                if (isset($f['status']) AND $f['status'] == 1) {
                    $found['class_name_I'] = $f['result'];
                } else {
                    throw new Exception($f['report']);
                }
                $f = $this->parse_name_for_class_II($class_id);
                if (isset($f['status']) AND $f['status'] == 1) {
                    $found['class_name_II'] = $f['result'];
                } else {
                    throw new Exception($f['report']);
                }
                $co = $this->get_coarse_name($coarse_id);
                $coarses = null;
                if (isset($co['status']) AND $co['status'] == 1) {
                    $coarses = $co['result'];
                } else {
                    throw new Exception($co['report']);
                }
                $found['coarse'] = $coarses;
                $res['report'] = $found;
            } elseif ($class_ids == null) {
                $res['result'] = "No class registered under the specified lecturer";
            }
        } catch (PDOException $e) {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }

    public function get_coarse_name($coarse_id)
    {
        $res = array();
        $res['status'] = 1;
        try {
            $name = "";
            $sql = "SELECT coarseName FROM coarses WHERE id=:id";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':id', $coarse_id, PDO::PARAM_INT);
            $stmt->execute();
            $response = $stmt->fetch();
            if (isset($response['coarseName']) AND $response['coarseName'] != "") {
                $name .= $response['coarseName'];

            } else {
                throw new PDOException("Coarse name Couldn't be retrieved from the database");
            }
            $res['result'] = $name;
        } catch (PDOException $e) {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }

    ///////////////////////////////////////
    public function get_assignments_for_class($class_id)
    {
        $res = array();
        $res['status'] = 1;
        try {
            $sql = "SELECT * FROM assignments WHERE classID=:cls_id";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':cls_id', $class_id, PDO::PARAM_INT);
            $result = $stmt->execute();
            $data = array();
            while ($row = $stmt->fetch()) {
                $data[] = $row;
            }
            $res['result'] = $data;
        } catch (PDOException $e) {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }

    public function get_assignment_by_id($ass_id)
    {
        $res = array();
        $res['status'] = 1;
        try {
            $sql = "SELECT * FROM assignments WHERE id=:id";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':id', $ass_id, PDO::PARAM_INT);
            $result = $stmt->execute();
            $res['result'] = $stmt->fetch();
        } catch (PDOException $e) {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }

    public function get_assignments_for_user($IMEI)
    {
        $res = array();
        $res['status'] = 1;
        try {
            $user_id = $this->get_user_id($IMEI)['result']['id'];
            $class_id = $this->get_classID_by_userID($user_id)['result']['classID'];
            $sql = "SELECT * FROM assignments WHERE classID=:cls_id";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':cls_id', $class_id, PDO::PARAM_INT);
            $result = $stmt->execute();
            $data = array();
            while ($row = $stmt->fetch()) {
                $data[] = $row;
            }
            $res['result'] = $data;
        } catch (PDOException $e) {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }

    public function get_assignments_for_user_id($user_id)
    {
        $res = array();
        $res['status'] = 1;
        try {

            $class = $this->get_classID_by_userID($user_id);
            if (isset($class['status']) AND $class['status'] == 1) {
                $class_id = $class['result']["classID"];
            } else {
                throw new PDOException($class['report']);
            }
            $sql = "SELECT * FROM assignments WHERE classID=:cls_id";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':cls_id', $class_id, PDO::PARAM_INT);
            $result = $stmt->execute();
            $data = array();
            while ($row = $stmt->fetch()) {
                $data[] = $row;
            }
            $res['result'] = $data;
        } catch (PDOException $e) {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }

    public function get_assignments_by_lect_id($lect_id)
    {
        $res = array();
        $res['status'] = 1;
        $res['result'] = array();
        try {


            $sql = "SELECT * FROM assignments WHERE lectID=:lect_id";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':lect_id', $lect_id, PDO::PARAM_INT);
            $result = $stmt->execute();
            $data = array();
            while ($row = $stmt->fetch()) {
                $res['result'][] = $row;
            }

        } catch (PDOException $e) {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }

    public function get_user_fullname($user_id)
    {
        $res = array();
        $res['status'] = 1;
        try {
            $sql = "SELECT fullname FROM userprofiles WHERE MobUserid=:id";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':id', $user_id, PDO::PARAM_INT);
            $stmt->execute();
            $row = $stmt->fetch();
            if (isset($row) AND $row != "") {
                $res['result'] = $row;
            } else {
                $res['status'] = 0;
                $res['report'] = "No user registered under the specified id";
            }
        } catch (PDOException $e) {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }

    public function is_valid_user($username, $password)
    {
        $res = array();
        $res['status'] = 1;
        $res['result'] = null;
        try {
            $sql = "SELECT id, username, active FROM users WHERE password=password(:pass)";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':pass', $password, PDO::PARAM_STR);
            $stmt->execute();
            while ($row = $stmt->fetch()) {
                if ($row['username'] === $username) {
                    $res['result'] = $row;
                    break;
                }
            }
        } catch (PDOException $e) {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }

    public function is_lecturer($user_id)
    {
        $res = array();
        $res['status'] = 1;
        $res['result'] = null;
        try {
            $sql = "SELECT * FROM lecturers WHERE userID=:user_id";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->execute();
            while ($row = $stmt->fetch()) {
                if ($row['userID'] == $user_id) {
                    $res['result'] = $row;
                    break;
                }
            }
        } catch (PDOException $e) {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }

    public function is_student($user_id)
    {
        $res = array();
        $res['status'] = 1;
        $res['result'] = null;
        try {
            $sql = "SELECT * FROM userprofiles WHERE MobUserId=:user_id";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->execute();
            while ($row = $stmt->fetch()) {
                if ($row['MobUserId'] == $user_id) {
                    $res['result'] = $row;
                    break;
                }
            }
        } catch (PDOException $e) {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }

    public function get_user_profile_by_id($id)
    {
        $res = array();
        $res['status'] = 1;
        try {
            $sql = "SELECT * FROM userprofiles WHERE id=:id";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $res['result'] = $stmt->fetch();
        } catch (PDOException $e) {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }

    public function get_lecturer_by_id($id)
    {
        $res = array();
        $res['status'] = 1;
        try {
            $sql = "SELECT * FROM lecturers WHERE id=:id";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $res['result'] = $stmt->fetch();
        } catch (PDOException $e) {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }

    public function get_admin_detail_by_id($admin_id)
    {
        $res = array();
        $res['status'] = 1;
        try {
            $sql = "SELECT fullname, privilage_id, representedentity, scopeID, created FROM admins WHERE id=:id";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':id', $admin_id, PDO::PARAM_INT);
            $stmt->execute();
            $res['result'] = $stmt->fetch();
        } catch (PDOException $e) {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }

    public function is_username_taken($user_name)
    {
        $res = array();
        $res['status'] = 1;
        $flag = false;
        $flag2 = false;
        try {
            $sql = "SELECT * FROM users WHERE username=:u_name";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':u_name', $user_name, PDO::PARAM_STR);
            $stmt->execute();
            while ($row = $stmt->fetch()) {
                $flag = true;
                break;
            }
            if ($flag) {
                $res['result'] = 1;
            } else {
                $sql = "SELECT * FROM admins WHERE username=:u_name";
                $stmt = $this->connection->prepare($sql);
                $stmt->bindvalue(':u_name', $user_name, PDO::PARAM_STR);
                $stmt->execute();
                while ($row = $stmt->fetch()) {
                    $flag2 = true;
                    break;
                }
                if ($flag2) {
                    $res['result'] = 1;
                } else {
                    $res['result'] = 0;
                }
            }
        } catch (PDOException $e) {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }

    public function get_result_for_id($stud_id, $report_file)
    {
        $res = array();
        $res['status'] = 1;
        try {
            $report_file1 = $_SERVER['DOCUMENT_ROOT']."/Alekas/private/files/results/".$report_file;
            $result = "";
            $reader = PHPExcel_IOFactory::createReaderForFile($report_file1);
            if ($reader->canRead($report_file1)) {
                $file = $reader->load($report_file1);
                $file->setActiveSheetIndex(0);
                $active_sheet = $file->getActiveSheet();
                $i = 1;
                while ($active_sheet->getCell("A$i")->getValue() != "") {
                    if ($active_sheet->getCell("A$i")->getValue() == $stud_id) {
                        $result = $active_sheet->getCell("B$i")->getValue();
                        if ($result == "") {
                            throw new Exception("No result registered under your id");
                        }
                        break;
                    }
                    $i++;
                }
                if ($result == "") {
                    throw new Exception("No result registered under your id...");
                } else {
                    $res['result'] = $result;
                }

            } else {
                throw new Exception("Failed to read report file");
            }
        } catch (Exception $e) {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }

    public function import_departments_from_excel($file)
    {
        $res = array();
        $deps = array();
        $res['status'] = 1;
        $reader = PHPExcel_IOFactory::createReader("Excel5");
        try {
            if ($reader->canRead($file)) {
                $_file_ = $reader->load($file);
                $_file_->setActiveSheetIndex(0);
                $active_sheet = $_file_->getActiveSheet();
                $i = 1;
                while ($active_sheet->getCell("A$i") != "") {
                    array_push($deps, $active_sheet->getCell("A$i")->getValue());
                    $i++;

                }
                foreach ($deps as $dep) {
                    $response = $this->add_department($dep);
                    if (isset($response['status']) AND $response['status'] == 1) {

                    } else {
                        throw new Exception($response['report']);
                    }
                }
            } else {
                throw new Exception("Failed to read file");
            }
        } catch (Exception $e) {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }

    public function get_all_classes_in_dept($dept_id)
    {
        $res = array();
        $res['status'] = 1;
        try {
            $sql = "SELECT id FROM classes WHERE department_id=:d_id";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':d_id', $dept_id, PDO::PARAM_INT);
            $stmt->execute();
            $res['result'] = array();
            while ($row = $stmt->fetch()) {
                $res['result'][] = $row;
            }
        } catch (PDOException $e) {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }

    public function drop_all_schedules_for_dept($dept_id)
    {
        $res = array();
        $res['status'] = 1;
        try {
            $classes = array();
            $clas = $this->get_all_classes_in_dept($dept_id);
            if (isset($clas['status']) ANd $clas['status'] == 1) {
                $classes = $clas['result'];
            } else {
                throw new Exception($clas['report']);
            }
            foreach ($classes as $class) {
                $drop = $this->drop_all_schedules_for_class($clas['id']);
            }
        } catch (Exception $e) {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }

    public function drop_all_schedules_for_class($class_id)
    {
        $res = array();
        $res['status'] = 1;
        try {
            $sql = "DELETE FROM schedules WHERE classID=:c_id";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':d_id', $class_id, PDO::PARAM_INT);
            $stmt->execute();
        } catch (PDOException $e) {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }

    public function get_all_sections_for($prog_name, $dept_name, $year, $stream_name)
    {
        $res = array();
        $res['status'] = 1;
        $prog_id = 1;
        $dept_id = 1;
        $stream_id = 1;
        try {

            $prog = $this->get_program_id($prog_name);
            if (isse($prog['status']) AND $prog['status'] == 1) {
                $prog_id = $prog['id'];
            } else {
                throw new Exception("Error:: program name couldn't be identified");
            }

            $dept = $this->get_dept_id($dept_name);
            if (isse($dept['status']) AND $dept['status'] == 1) {
                $dept_id = $dept['id'];
            } else {
                throw new Exception("Error:: Department name couldn't be identified");
            }


            $str = $this->get_stream_id($stream_name, $dept_id);
            if (isse($str['status']) AND $str['status'] == 1) {
                $stream_id = $str['id'];
            } else {
                throw new Exception("Error:: Stream name couldn't be identified");
            }

            $res['result'] = array();
            $sql = "SELECT * FROM programs WHERE  program_id=:p_id AND year=:yr AND stream_id=:str_id AND dept_id=:d_id";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':p_id', $prog_id, PDO::PARAM_INT);
            $stmt->bindvalue(':yr', $year, PDO::PARAM_INT);
            $stmt->bindvalue(':str_id', $stream_id, PDO::PARAM_INT);
            $stmt->bindvalue(':d_id', $dept_id, PDO::PARAM_INT);
            $stmt->execute();
            while ($row = $stmt->fetch()) {
                $res['result'][] = $row;
            }
        } catch (Exception $e) {
            $res['status'] = 1;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }

    public function import_streams_from_excel($file, $dept_id)
    {
        $res = array();
        $strms = array();
        $res['status'] = 1;
        $reader = PHPExcel_IOFactory::createReader("Excel5");
        try {
            if ($reader->canRead($file)) {
                $_file_ = $reader->load($file);
                $_file_->setActiveSheetIndex(0);
                $active_sheet = $_file_->getActiveSheet();
                $i = 1;
                while ($active_sheet->getCell("A$i") != "") {
                    array_push($strms, $active_sheet->getCell("A$i")->getValue());
                    $i++;

                }
                foreach ($strms as $strm) {
                    $response = $this->add_stream($dept_id, $strm);
                    if (isset($response['status']) AND $response['status'] == 1) {

                    } else {
                        throw new Exception($response['report']);
                    }
                }
            } else {
                throw new Exception("Failed to read file");
            }
        } catch (Exception $e) {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }





    ///Newly Added Codes
    /////////////////////////////////////////////////////////////////////////////////////////////////////////
    //********************************************************************************************************
    public function get_lecturer_by_userID($userID)
    {
        $res = array();
        $res['status'] = 1;
        try {
            $sql = "SELECT * FROM lecturers WHERE user_id= :us_cd";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':us_cd', $userID, PDO::PARAM_INT);
            $stmt->execute();
            $res['result'] = $stmt->fetch();
        } catch (PDOException $e) {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }

    public function get_assignments_for_STD($IMEI)
    {
        $res = array();
        $res['status'] = 1;
        try {
            $user_id = $this->get_user_id($IMEI)['result']['id'];
            $class_id = $this->get_classID_by_userID($user_id)['result']['classID'];
            $sql = "SELECT * FROM assignments WHERE classID=:cls_id";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':cls_id', $class_id, PDO::PARAM_INT);
            $stmt->execute();
            $data = array();
            while ($row = $stmt->fetch()) {
                $data[] = $row;
            }
            $res['result'] = $data;
        } catch (PDOException $e) {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }

    public function get_assignments_for_LEC($IMEI)
    {
        $res = array();
        $res['status'] = 1;
        try {
            $user_id = $this->get_user_id($IMEI)['result']['id'];
            $class_id = $this->get_classID_by_userID($user_id)['result']['classID'];
            $sql = "SELECT * FROM assignments WHERE lectID=:lec_id";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':lec_id', $class_id, PDO::PARAM_INT);
            $stmt->execute();
            $data = array();
            while ($row = $stmt->fetch()) {
                $data[] = $row;
            }
            $res['result'] = $data;
        } catch (PDOException $e) {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }

    public function get_userProfile($User_ID)
    {
        $res = array();
        $res['status'] = 1;
        try {
            $sql = "SELECT * FROM userprofiles WHERE MobUserId= :us_id";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':us_id', $User_ID, PDO::PARAM_INT);
            $stmt->execute();
            $res['result'] = $stmt->fetch();
        } catch (PDOException $e) {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }

    public function update_lecturer($id, $user_id)
    {
        $res = array();
        $res['status'] = 1;
        try {
            $sql = "UPDATE lecturers SET userID=:us_id WHERE id=:id";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':id', $id, PDO::PARAM_INT);
            $stmt->bindvalue(':us_id', $user_id, PDO::PARAM_INT);
            $stmt->execute();
        } catch (PDOException $e) {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }

    public function delete_userProfile($user_id)
    {
        $res = array();
        $res['status'] = 1;
        try {
            $sql = "DELETE FROM `userprofiles` WHERE MobUserId=:id";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':id', $user_id, PDO::PARAM_INT);
            $stmt->execute();
        } catch (PDOException $e) {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }

    public function get_staffcode_for_lect_fullname($full_name)
    {
        $res = array();
        $res['status'] = 1;
        $res['result'] = null;
        try {
            $sql = "SELECT staffCode FROM lecturers WHERE FullName=:f_name";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':f_name', $full_name, PDO::PARAM_STR);
            $stmt->execute();
            $res['result'] = $stmt->fetch();
        } catch (PDOException $e) {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }

    public function get_user_id_($user_name)
    {
        $res = array();
        $res['status'] = 1;
        try {
            $sql = "SELECT id FROM users WHERE userName=:u_name";
            $stmt = $this->connection->prepare($sql);
            $stmt->bind_value(':u_name', $user_name, PDO::PARAM_STR);
            $stmt->execute();
            $res['result'] = $stmt->fetch();
        } catch (PDOException $e) {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }

    public function register_lecturer($user_id, $fullname, $email, $phone, $office_number)
    {
        $res = array();
        $res['status'] = 1;
        try {
            $sql = "UPDATE lecturers SET FullName=:f_name, email=:em, phone=:pho, officeNumber=:of_num WHERE userID=:u_id ";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':f_name', $fullname, PDO::PARAM_STR);
            $stmt->bindvalue(':em', $email, PDO::PARAM_STR);
            $stmt->bindvalue(':of_num', $office_number, PDO::PARAM_STR);
            $stmt->bindvalue(':pho', $phone, PDO::PARAM_STR);
            $stmt->bindvalue(':u_id', $user_id, PDO::PARAM_INT);
            $stmt->execute();
        } catch (PDOException $e) {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }
    public function register_lecturer_($user_id, $fullname, $email, $phone, $office_number, $staff_code)
    {
        $res = array();
        $res['status'] = 1;
        try {
            $sql = "UPDATE lecturers SET email=:em, phone=:pho, officeNumber=:of_num, userID=:u_id WHERE staffCode=:s_code AND FullName=:f_name";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':f_name', $fullname, PDO::PARAM_STR);
            $stmt->bindvalue(':em', $email, PDO::PARAM_STR);
            $stmt->bindvalue(':of_num', $office_number, PDO::PARAM_STR);
            $stmt->bindvalue(':pho', $phone, PDO::PARAM_STR);
            $stmt->bindvalue(':u_id', $user_id, PDO::PARAM_INT);
            $stmt->bindvalue(':s_code', $staff_code, PDO::PARAM_INT);
            $stmt->execute();
        } catch (PDOException $e) {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }

    public function get_all_notifications_by($sender)
    {
        $res = array();
        $res['status'] = 1;
        try {
            $sql = "SELECT * FROM notifications WHERE sender=:sender GROUP BY body";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':sender', $sender, PDO::PARAM_STR);
            $stmt->execute();
            $res['result'] = array();
            while ($row = $stmt->fetch()) {
                $res['result'][] = $row;
            }
        } catch (PDOException $e) {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }

    public function get_notif_body($id)
    {
        $res = array();
        $res['status'] = 1;
        try {
            $sql = "SELECT body, starttime FROM notifications WHERE id=:id";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $res['result'] = $stmt->fetch();
        } catch (PDOException $e) {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }

    public function remove_notifications_with_same_body_as($id, $u_id)
    {
        $res = array();
        $res['status'] = 1;
        $body = "";
        $start_time = "";
        try {
            $bo = $this->get_notif_body($id, $u_id);
            if (isset($bo['status']) and $bo['status'] == 1) {
                $body = $bo['result']['body'];
                $start_time = $bo['result']['starttime'];
            } else {
                throw new PDOException($bo['report']);
            }
            $sql = "DELETE FROM notifications WHERE body=:body AND userID=:u_id AND starttime=:str_time";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':body', $body, PDO::PARAM_STR);
            $stmt->bindvalue(':u_id', $u_id, PDO::PARAM_INT);
            $stmt->bindvalue(':str_time', $start_time, PDO::PARAM_STR);
            $stmt->execute();
        } catch (PDOException $e) {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }

    /// ye robel hayelom dinber new... wa tshager ena


    public function remove_admin($id)
    {
        $res = array();
        $res['status'] = 1;
        try {
            $sql = "DELETE FROM admins WHERE id=:id";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
        } catch (PDOException $e) {
            $res['status'] == 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }

    public function get_courses_of_lec($lec_id)
    {
        $res = array();
        $res['status'] = 1;
        try {
            $sql = "SELECT coarseID FROM schedules WHERE lecturerID= :lec_id GROUP BY coarseID";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':lec_id', $lec_id, PDO::PARAM_INT);
            $stmt->execute();
            $res['result'] = array();
            while ($row = $stmt->fetch()) {
                $res['result'][] = $row;
            }
        } catch (PDOException $e) {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }

    public function get_assignment_id_by_name($ass_name)
    {
        $res = array();
        $res['status'] = 1;
        try {
            $sql = "SELECT id FROM assignments WHERE assignments.name= :a_name";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':a_name', $ass_name, PDO::PARAM_STR);
            $stmt->execute();
            $res['result'] = $stmt->fetch();
        } catch (PDOException $e) {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }

    public function get_lecturers_unique_class_ids($lect_id)
    {
        $res = array();
        $res['status'] = 1;
        try {
            $sql = "SELECT DISTINCT classID FROM schedules WHERE lecturerID= :lect_id";//" GROUP BY classID";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':lect_id', $lect_id, PDO::PARAM_STR);
            $stmt->execute();
            while ($row = $stmt->fetch()) {
                $res['result'][] = $row;
            }
        } catch (PDOException $e) {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }




    //////////////////////////////////////////////////////////////////////////////
    //
    //methods below are about coarse add and drop
    public function drop_corse_for_student($co_id, $student_id)
    {
        $res = array();
        $res['status'] = 1;
        try {
            $sql = "INSERT INTO add_and_drops(coarse_id, student_id, add_and_drops.type) VALUES(:co_id, :st_id, 'drop')";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':co_id', $co_id, PDO::PARAM_INT);
            $stmt->bindvalue(':st_id', $student_id, PDO::PARAM_INT);
            $stmt->execute();
        } catch (PDOException $e) {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }

    public function add_corse_for_student($coarse_id, $student_id)
    {
        $res = array();
        $res['status'] = 1;
        try {
            $sql = "INSERT INTO add_and_drops(coarse_id, student_id, add_and_drops.type) VALUES(:co_id, :st_id, 'add')";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':co_id', $coarse_id, PDO::PARAM_INT);
            $stmt->bindvalue(':st_id', $student_id, PDO::PARAM_INT);
            $stmt->execute();
        } catch (PDOException $e) {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }

    public function remove_corse_drop($coarse_id, $student_id)
    {
        $res = array();
        $res['status'] = 1;
        try {
            $sql = "DELETE FROM add_and_drops WHERE coarse_id=:co_id AND student_id=:st_id AND add_and_drops.type='drop'";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':co_id', $coarse_id, PDO::PARAM_INT);
            $stmt->bindvalue(':st_id', $student_id, PDO::PARAM_INT);
            $stmt->execute();
        } catch (PDOException $e) {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }

    public function remove_coarse_add($coarse_id, $student_id)
    {
        $res = array();
        $res['status'] = 1;
        try {
            $sql = "DELETE FROM add_and_drops WHERE coarse_id=:co_id AND student_id=:st_id AND add_and_drops.type='add'";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':co_id', $coarse_id, PDO::PARAM_INT);
            $stmt->bindvalue(':st_id', $student_id, PDO::PARAM_INT);
            $stmt->execute();
        } catch (PDOException $e) {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }

    public function get_added_coarses_for_student($student_id)
    {
        $res = array();
        $res['status'] = 1;
        try {
            $sql = "SELECT * FROM add_and_drops WHERE student_id=:st_id AND add_and_drops.type='add'";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':st_id', $student_id, PDO::PARAM_INT);
            $stmt->execute();
            $res['result'] = array();
            while ($row = $stmt->fetch()) {
                $res['result'][] = $row;
            }
        } catch (PDOException $e) {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }

    public function get_droped_coarses_for_student($student_id)
    {
        $res = array();
        $res['status'] = 1;
        try {
            $sql = "SELECT * FROM add_and_drops WHERE student_id=:st_id AND add_and_drops.type='drop'";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':st_id', $student_id, PDO::PARAM_INT);
            $stmt->execute();
            $res['result'] = array();
            while ($row = $stmt->fetch()) {
                $res['result'][] = $row;
            }
        } catch (PDOException $e) {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }

    public function get_courses_for_class($class_id)
    {
        $res = array();
        $res['status'] = 1;
        try {
            $sql = "SELECT id, coarseName, coarseCode FROM coarses WHERE id IN(SELECT DISTINCT coarseID FROM schedules WHERE classID=:cls_id GROUP  BY coarseID)";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue('cls_id', $class_id, PDO::PARAM_INT);
            $stmt->execute();
            $res['result'] = array();
            while ($row = $stmt->fetch()) {
                $res['result'][] = $row;
            }
        } catch (PDOException $e) {
            $res['status'] = 0;
            $res["report"] = $e->getMessage();
        }
        return $res;
    }

    public function get_course($course_id)
    {
        $res = array();
        $res['status'] = 1;
        try {
            $sql = "SELECT id, coarseName, coarseCode FROM coarses WHERE id=:c_id";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':c_id', $course_id, PDO::PARAM_INT);
            $stmt->execute();
            $res['result'] = array();
            $res['result'] = $stmt->fetch();
        } catch (PDOException $e) {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }

    public function get_courses_for_student($student_id)
    {
        $res = array();
        $res['status'] = 1;
        try {
            $class_id = null;
            $class_courses = null;
            $droped_courses = null;
            $added_courses = null;
            $user_courses = array();

            $resp = $this->get_student_classId($student_id);
            if (isset($resp['status']) AND $resp['status'] == 1) {
                $class_id = $resp['result'];
            } else {
                throw new PDOException($resp['report']);
            }

            $response = $this->get_courses_for_class($class_id);
            if (isset($response['status']) AND $response['status'] == 1) {
                $class_courses = $response['result'];
            } else {
                throw new PDOException($response['report']);
            }

            $d = $this->get_droped_coarses_for_student($student_id);
            if (isset($d['status']) AND $d['status'] == 1) {
                $droped_courses = $d['result'];
            } else {
                throw new PDOException($d['report']);
            }

            $a = $this->get_added_coarses_for_student($student_id);
            if (isset($a['status']) AND $a['status'] == 1) {
                $added_courses = $a['result'];
            } else {
                throw new PDOException($a['report']);
            }

            foreach ($class_courses as $course) {
                $flag = false;
                foreach ($droped_courses as $drop) {
                    if ($drop['coarse_id'] == $course['id']) {
                        $flag = true;
                        break;
                    }
                }
                if (!$flag) {
                    $user_courses[] = $course;
                }
            }
            foreach ($added_courses as $add) {
                $flag = false;
                foreach ($droped_courses as $drop) {
                    if ($drop['coarse_id'] == $add['coarse_id']) {
                        $flag = true;
                        break;
                    }
                }
                if (!$flag) {
                    $user_courses[] = $this->get_course($add['coarse_id']);
                }
            }
            $res['result'] = $user_courses;

        } catch (PDOException $e) {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }

    public function get_student_classId($user_profile_id)
    {
        $res = array();
        $res['status'] = 1;
        try {
            $sql = "SELECT classID FROM userprofiles WHERE id=:u_id";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':u_id', $user_profile_id, PDO::PARAM_INT);
            $stmt->execute();
            $res['result'] = $stmt->fetch()['classID'];
        } catch (PDOException $e) {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }

    public function get_lect_courses_for_class($lect_id, $class_id)
    {
        $res = array();
        $res['status'] = 1;
        try {
            $sql = "SELECT coarseID FROM schedules WHERE lecturerID=:lect AND classID=:cls group by coarseID";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':lect', $lect_id, PDO::PARAM_INT);
            $stmt->bindvalue(':cls', $class_id, PDO::PARAM_INT);
            $stmt->execute();
            $res['result'] = array();
            while ($row = $stmt->fetch()) {
                $res['result'][] = $row;
            }
        } catch (PDOException $e) {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }

    public function update_lecturer_full_name($id, $new_fullname)
    {
        $res = array();
        $res['status'] = 1;
        try {
            $sql = "UPDATE lecturers SET FullName=:f_name WHERE id=:l_id";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':f_name', $new_fullname, PDO::PARAM_STR);
            $stmt->bindvalue(':l_id', $id, PDO::PARAM_INT);
            $stmt->execute();
        } catch (PDOException $e) {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }

    public function update_lecturer_phone($id, $new_phone)
    {
        $res = array();
        $res['status'] = 1;
        try {
            $sql = "UPDATE lecturers SET phone=:pho WHERE id=:l_id";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':pho', $new_phone, PDO::PARAM_STR);
            $stmt->bindvalue(':l_id', $id, PDO::PARAM_INT);
            $stmt->execute();
        } catch (PDOException $e) {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }

    public function update_lecturer_office_num($id, $new_office_num)
    {
        $res = array();
        $res['status'] = 1;
        try {
            $sql = "UPDATE lecturers SET officeNumber=:off_no WHERE id=:l_id";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':off_no', $new_office_num, PDO::PARAM_STR);
            $stmt->bindvalue(':l_id', $id, PDO::PARAM_INT);
            $stmt->execute();
        } catch (PDOException $e) {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }

    public function update_lecturer_staff_code($id, $new_staff_code)
    {
        $res = array();
        $res['status'] = 1;
        try {
            $sql = "UPDATE lecturers SET staffCode=:staff_code WHERE id=:l_id";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':staff_code', $new_staff_code, PDO::PARAM_STR);
            $stmt->bindvalue(':l_id', $id, PDO::PARAM_INT);
            $stmt->execute();
        } catch (PDOException $e) {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }

    public function change_password_for_user($id, $pass)
    {
        $res = array();
        $res['status'] = 1;
        try {
            $sql = "UPDATE users SET password=password(:pass) WHERE id=:u_id";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':pass', $pass, PDO::PARAM_STR);
            $stmt->bindvalue(':u_id', $id, PDO::PARAM_INT);
            $stmt->execute();
        } catch (PDOException $e) {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }

    public function deactivate_account($id)
    {
        $res = array();
        $res['status'] = 1;
        try {
            $sql = "UPDATE users SET active = '0' WHERE id=:u_id";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':u_id', $id, PDO::PARAM_INT);
            $stmt->execute();
        } catch (PDOException $e) {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }

    public function activate_account($id)
    {
        $res = array();
        $res['status'] = 1;
        try {
            $sql = "UPDATE users SET active='1' WHERE id=:u_id";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':u_id', $id, PDO::PARAM_INT);
            $stmt->execute();
        } catch (PDOException $e) {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }

    public function update_student_fullname($id, $name)
    {
        $res = array();
        $res['status'] = 1;
        try {
            $sql = "UPDATE userprofiles SET FullName=:f_name WHERE id=:s_id";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':f_name', $name, PDO::PARAM_STR);
            $stmt->bindvalue(':s_id', $id, PDO::PARAM_INT);
            $stmt->execute();
        } catch (PDOException $e) {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }

    public function update_student_schoolID($id, $schoolID)
    {
        $res = array();
        $res['status'] = 1;
        try {
            $sql = "UPDATE userprofiles SET SchoolID=:sch_id WHERE id=:s_id";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':sch_id', $schoolID, PDO::PARAM_STR);
            $stmt->bindvalue(':s_id', $id, PDO::PARAM_INT);
            $stmt->execute();
        } catch (PDOException $e) {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }

    public function update_student_email($id, $email)
    {
        $res = array();
        $res['status'] = 1;
        try {
            $sql = "UPDATE userprofiles SET Email=:s_email WHERE id=:s_id";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':s_email', $email, PDO::PARAM_STR);
            $stmt->bindvalue(':s_id', $id, PDO::PARAM_INT);
            $stmt->execute();
        } catch (PDOException $e) {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }

    public function update_student_phone($id, $phone)
    {
        $res = array();
        $res['status'] = 1;
        try {
            $sql = "UPDATE userprofiles SET phone=:s_phone WHERE id=:s_id";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':s_phone', $phone, PDO::PARAM_STR);
            $stmt->bindvalue(':s_id', $id, PDO::PARAM_INT);
            $stmt->execute();
        } catch (PDOException $e) {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }

    public function get_student_user_id($profile_id)
    {
        $res = array();
        $res['status'] = 1;
        try {
            $sql = "SELECT MobUserId FROM userprofiles WHERE id=:p_id";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':p_id', $profile_id, PDO::PARAM_INT);
            $stmt->execute();
            $res['result'] = $stmt->fetch();
        } catch (PDOException $e) {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }

    public function get_recent_assignments($class_id)
    {
        $res = array();
        $res['status'] = 1;
        try {
            $sql = "SELECT * FROM assignments WHERE classID=:c_id AND submissionDate >= CURRENT_TIMESTAMP ORDER BY (submissionDAte - CURRENT_TIMESTAMP) ASC ";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':c_id', $class_id, PDO::PARAM_INT);
            $stmt->execute();
            $res['result'] = array();
            while ($row = $stmt->fetch()) {
                $res['result'][] = $row;
            }
        } catch (PDOException $e) {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }

    public function get_recently_passed_assignments($class_id)
    {
        $res = array();
        $res['status'] = 1;
        try {
            $sql = "SELECT * FROM assignments WHERE classID=:c_id AND submissionDate < CURRENT_TIMESTAMP ORDER BY (CURRENT_TIMESTAMP - submissionDAte) ASC ";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':c_id', $class_id, PDO::PARAM_INT);
            $stmt->execute();
            $res['result'] = array();
            while ($row = $stmt->fetch()) {
                $res['result'][] = $row;
            }
        } catch (PDOException $e) {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }

    public function get_mysql_time()
    {
        $res = array();
        $res['status'] = 1;
        try {
            $sql = "SELECT CURRENT_TIMESTAMP ";
            $stmt = $this->connection->prepare($sql);
            $stmt->execute();
            $res['result'] = array();
            $res['result'] = $stmt->fetch();
        } catch (PDOException $e) {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }

    public function check_assignment_not_submitted($ass_id, $stud_id)
    {
        $res = array();
        $res['status'] = 1;
        try {
            $sql = "SELECT * FROM posts WHERE userID=:u_id AND assignmentID=:a_id AND seen = '1'";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':u_id', $stud_id, PDO::PARAM_INT);
            $stmt->bindvalue(':a_id', $ass_id, PDO::PARAM_INT);
            $stmt->execute();
            $res['result'] = null;
            while ($row = $stmt->fetch()) {
                $res['result'] = $row;
            }
        } catch (PDOException $e) {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }
    public function check_post_exists($ass_id, $stud_id)
    {
        $res = array();
        $res['status'] = 1;
        try {
            $sql = "SELECT * FROM posts WHERE userID=:u_id AND assignmentID=:a_id";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':u_id', $stud_id, PDO::PARAM_INT);
            $stmt->bindvalue(':a_id', $ass_id, PDO::PARAM_INT);
            $stmt->execute();
            $res['result'] = null;
            while ($row = $stmt->fetch()) {
                $res['result'] = $row;
            }
        } catch (PDOException $e) {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }

    public function create_event_news($site_id, $title, $description, $pub_date, $link, $img_link)
    {
        $res = array();
        $res['status'] = 1;
        try {
            $sql = "INSERT INTO rss_feeds(rss_site_id, title, description, pub_date, link, img_link) VALUES(:rss_s_id, :title, :description, :p_date, :link, :i_link)";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(":rss_s_id", $site_id, PDO::PARAM_INT);
            $stmt->bindvalue(":title", $title, PDO::PARAM_STR);
            $stmt->bindvalue(":description", $description, PDO::PARAM_STR);
            $stmt->bindvalue(":p_date", $pub_date, PDO::PARAM_STR);
            $stmt->bindvalue(":link", $link, PDO::PARAM_STR);
            $stmt->bindvalue(":i_link", $img_link, PDO::PARAM_STR);
            $stmt->execute();
        } catch (PDOException $e) {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
            $this->log_error($res['report']);
        }
        return $res;
    }
    public function get_rss_site_id_by_catagory($catagory)
    {
        $res = array();
        $res['status'] = 1;
        try{
            $sql = "SELECT id FROM rss_sites WHERE rss_sites.category=:cat";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':cat', $catagory, PDO::PARAM_INT);
            $stmt->execute();
            $res['result'] = $stmt->fetch();
        }catch(PDOException $e)
        {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
            $this->log_error($res['report']);
        }
        return $res;
    }
    public function get_all_recent_events_under_site_id($site_id)
    {
        $res = array();
        $res['status'] = 1;
        try{
            $sql = "SELECT * FROM rss_feeds WHERE rss_site_id=:r_s_id order by id DESC";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':r_s_id', $site_id, PDO::PARAM_INT);
            $stmt->execute();
            $res['result'] = array();
            while($row = $stmt->fetch())
            {
                $res['result'][] = $row;
            }
        }catch(PDOException $e)
        {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
            $this->log_error($res['report']);
        }
        return $res;
    }
    public function get_all_recent_events()
    {
        $res = array();
        $res['status'] = 1;
        try{
            $sql = "SELECT * FROM rss_feeds order by id DESC";
            $stmt = $this->connection->prepare($sql);
            $stmt->execute();
            $res['result'] = array();
            while($row = $stmt->fetch())
            {
                $res['result'][] = $row;
            }
        }catch(PDOException $e)
        {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
            $this->log_error($res['report']);
        }
        return $res;
    }
    public function get_all_catagories()
    {
        $res = array();
        $res['status'] = 1;
        try{
            $sql = "SELECT category FROM rss_sites GROUP BY category";
            $stmt = $this->connection->prepare($sql);
            $stmt->execute();
            $res['result'] =array();
            while($row = $stmt->fetch())
            {
                if($row != NULL)
                {
                    $res['result'][] = $row;
                }
            }
        }catch(PDOException $e)
        {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
            $this->log_error($res['report']);
        }
        return $res;
    }
    public function get_Feeds_for_category_($category_name)
    {
        $res = array();
        $res['status'] = 1;
        $res['result'] = array();
        try{
            $sites = array();
            $sit = $this->get_RSS_Sites_By_Category($category_name);
            if(isset($sit['status'])AND $sit['status'] = 1)
            {
                $sites = $sit['result'];
                $res['result'] = $this->get_latest_feeds_from_sites_in_site_list($sites)['result'];

            }else{
                throw new PDOException($sit['report']);
            }
        }catch(PDOException $e)
        {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
            $this->log_error($res['report']);
        }
        return $res;
    }
    public function get_latest_feeds_from_sites_in_site_list($site_list)
    {
        $res = array();
        $res['status'] = 1;
        $res['result'] = array();
        try{
            $sql = "SELECT * FROM rss_feeds WHERE ";
            if(is_array($site_list))
            {
                $i = 0;
                foreach($site_list as $site)
                {
                    $i++;
                    $sql .= " rss_site_id='".$site['id']."' OR";
                }
                $sql .= "DER BY id DESC";
            }
            $stmt = $this->connection->prepare($sql);
            $stmt->execute();
            while($row = $stmt->fetch())
            {
                $res['result'][] = $row;
            }
        }catch(PDOException $e)
        {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
            $this->log_error($res['report']);
        }
        return $res;
    }

    public function Update_News_Feed_1()
    {
        $RSS_Sites = $this->get_All_RSS_Site();

        if (isset($RSS_Sites) && isset($RSS_Sites['result'])) {
            $RSS_Sites = $RSS_Sites['result'];
            foreach ($RSS_Sites as $RSS_SITE) {

                $rss = simplexml_load_file($RSS_SITE['link']);
                $News_ID = $RSS_SITE['id'];

                $article[] = array();

                foreach ($rss->channel->item as $items) {

                    $media = $items->children('http://search.yahoo.com/mrss/');
                    // print_r($media->thumbnail[0]->attributes());

//                    foreach ($media->thumbnail[0]->attributes() as $key => $value) {
//                        $image[$key] = (string)$value;
//                    }

                    $article = array(
                        'title' => (string)$items->title,
                        'description' => (string)$items->description,
                        'link' => (string)$items->link,
                        'publication' => (string)$items->pubDate,);
//                        'image' => $image,);


                    $addFeed = $this->add_new_RSS_Feed($News_ID, $article['title'], $article['description'], $article['publication'], $article['link'], 's');


                }

            }
        }
    }



    ////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////
    ////////////////////////////////////////////////////////////


    public function get_user_by_IMEI($IMEI){
        $res = array();
        $res['status'] = 1;
        try{
            $sql = "SELECT * FROM users WHERE IMEI=:im_num";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':im_num', $IMEI, PDO::PARAM_STR);
            $stmt->execute();
            $res['result'] = $stmt->fetch();
        }catch(PDOException $e)
        {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }
    public function get_user_by_ID($ID){
        $res = array();
        $res['status'] = 1;
        try{
            $sql = "SELECT * FROM users WHERE id=:id_num";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':id_num', $ID, PDO::PARAM_STR);
            $stmt->execute();
            $res['result'] = $stmt->fetch();
        }catch(PDOException $e)
        {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }
    public function get_user_($UserName,$UserPass)
    {
        // this method retrieves the id to which user's mobile device is registered under to create a link between a registered
        // user in the mobuser table and other relations(tables) in the database like the user profile table
        // and notification log (noti_thankyou) tables...

        $res = array();
        $res['status'] = 1;
        try{
            $sql = "SELECT * FROM users WHERE userName=:us_nm AND users.password= password(:us_ps)";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':us_nm', $UserName, PDO::PARAM_STR);
            $stmt->bindvalue(':us_ps', $UserPass, PDO::PARAM_STR);
            $stmt->execute();
            $res['result'] = $stmt->fetch();
        }catch(PDOException $e)
        {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }
    public function is_user_($UserName,$UserPass)
    {
        $res = array();
        $res['status'] = 1;
        $res['result'] = 0;
        try{
            $sql = "SELECT * FROM users WHERE userName=:us_nm AND users.password= password(:us_ps)";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':us_nm', $UserName, PDO::PARAM_STR);
            $stmt->bindvalue(':us_ps', $UserPass, PDO::PARAM_STR);
            $stmt->execute();
            while($row =  $stmt->fetch()){
                $res['result'] = 1;
            }

        }catch(PDOException $e)
        {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }

    public function get_submitted_assignments_of_STD($IMEI)
    {
        $res = array();
        $res['status'] = 1;
        try{
            $user_id = $this->get_user_id($IMEI)['result']['id'];
            $class_id = $this->get_classID_by_userID($user_id)['result']['classID'];
            $sql = "SELECT A.id, A.name, A.lectID, A.coarseID, A.classID, A.filepath, A.submissionDate, A.description,P.fileName, P.seen,P.created FROM assignments as A,posts as P WHERE A.classID=:cls_id AND A.id = P.assignmentID";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':cls_id', $class_id, PDO::PARAM_INT);
            $stmt->execute();
            $data = array();
            while($row = $stmt->fetch())
            {
                $data[] = $row;

            }
            $res['result'] = $data;
        }catch(PDOException $e)
        {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }
    public function add_new_posts($User_ID, $Ass_ID, $FileName)
    {
        $res = array();
        $res['status'] = 1;
        try{
            $cur_time = date("Y-m-d H:i:s",time());
            $sql = "INSERT INTO posts(userID, assignmentID, fileName,created) VALUES(:us_id,:as_id,:fl_nm,:cr_dt)";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':us_id', $User_ID, PDO::PARAM_INT);
            $stmt->bindvalue(':as_id', $Ass_ID, PDO::PARAM_INT);
            $stmt->bindvalue(':fl_nm', $FileName, PDO::PARAM_STR);
            $stmt->bindvalue(':cr_dt', $cur_time, PDO::PARAM_STR);
            $stmt->execute();
        }catch (PDOException $e)
        {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }
    public function add_new_lecturer($dept_ID, $name, $phone, $office_number)
    {
        $res  = array();
        $res['status'] = 1;
        try{

            $sql = "INSERT INTO lecturers(deptID, FullName, phone, officeNumber, staffCode) VALUES(:dp_id, :fname, :phone, :office_num, :staff_code)";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':fname', $name, PDO::PARAM_STR);
            $stmt->bindvalue(':phone', $phone, PDO::PARAM_STR);
            $stmt->bindvalue(':office_num', $office_number, PDO::PARAM_STR);
            $stmt->bindvalue(':dp_id', $dept_ID, PDO::PARAM_INT);
            $stmt->bindvalue(':staff_code', $this->get_rand(5), PDO::PARAM_INT);
            $stmt->execute();
        }catch (PDOException $e)
        {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }

    public function get_rand($num){
        $const = "0123456789";
        $data = "";
        for($i=0;$i<$num;$i++){
            $data = $data.$const[rand(0,9)];
        }
        return $data;
    }
    public function get_all_stream_by_DeptID($Dept_ID)
    {
        $res = array();
        $res['status'] = 1;
        try{
            $sql= "SELECT * FROM streams WHERE dept_id=:dp_id ORDER  BY id";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':dp_id', $Dept_ID, PDO::PARAM_INT);
            $stmt->execute();
            $set = array();
            while($row = $stmt->fetch())
            {
                $set[] = $row;
            }
            $res['result'] = $set;
        }catch (PDOException $e)
        {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }

        return $res;
    }
    public function get_all_classes_by_deptID($dept_id)
    {
        $res = array();
        $res['status'] = 1;
        try{
            $sql = "SELECT * FROM classes WHERE department_id=:dp_id ORDER  BY program_id,department_id,stream_id,year,section_id";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':dp_id', $dept_id, PDO::PARAM_INT);
            $stmt->execute();
            $set = array();
            while($row = $stmt->fetch())
            {
                $set[] = $row;
            }
            $res['result'] = $set;
        }catch (PDOException $e)
        {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }
    public function get_all_lecturers_by_deptID($dept_id)
    {
        $res = array();
        $res['status'] = 1;
        try{
            $sql = "SELECT id, FullName, phone, officeNumber, staffCode FROM lecturers WHERE deptID=:dp_id ORDER BY id";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':dp_id', $dept_id, PDO::PARAM_INT);
            $stmt->execute();
            $set = array();
            while($row = $stmt->fetch())
            {
                $set[] = $row;
            }
            $res['result'] = $set;
        }catch (PDOException $e)
        {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }
    public function delete_class($class_id)
    {
        $res = array();
        $res['status'] = 1;
        try{
            $sql = "DELETE FROM classes WHERE id=:c_id";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':c_id', $class_id, PDO::PARAM_INT);
            $stmt->execute();
        }catch(PDOException $e)
        {
            $res['status'] =0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }

    public function delete_stream($stream_id)
    {
        $res = array();
        $res['status'] = 1;
        try{
            $sql = "DELETE FROM streams WHERE id=:c_id";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':c_id', $stream_id, PDO::PARAM_INT);
            $stmt->execute();
        }catch(PDOException $e)
        {
            $res['status'] =0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }
    public function delete_noti_by_ID($noti_id)
    {
        $res = array();
        $res['status'] = 1;
        try{
            $sql = "DELETE FROM notifications WHERE id=:c_id";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':c_id', $noti_id, PDO::PARAM_INT);
            $stmt->execute();
        }catch(PDOException $e)
        {
            $res['status'] =0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }
    public function get_all_years($dept)
    {
        $res = array();
        $res['status'] = 1;
        try{
            $sql = "SELECT * FROM classes WHERE department_id=:d_id";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':d_id', $dept, PDO::PARAM_INT);
            $stmt->execute();
            $res['result'] =array();
            while($row = $stmt->fetch())
            {
                $res['result'][] = $row;
            }
        }catch(PDOException $e)
        {
            $res['status'] =0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }
    public function get_all_sections_for_($dept_id)
    {
        try{
            $res['result'] = array();
            $sql = "SELECT * FROM classes WHERE department_id=:d_id";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':d_id', $dept_id, PDO::PARAM_INT);
            $stmt->execute();
            while($row = $stmt->fetch())
            {
                $res['result'][] = $row;
            }
        }catch(Exception $e)
        {
            $res['status'] = 1;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }
    public function get_notifications_by_category($category)
    {
        $res = array();
        $res['status'] = 1;
        try{
            $sql = "SELECT * FROM notifications WHERE category=:cat_id";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':cat_id', $category, PDO::PARAM_STR);
            $stmt->execute();
            $data = array();
            while($row = $stmt->fetch())
            {
                $data[] = $row;
            }
            $res['result'] = $data;
        }catch(PDOException $e)
        {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }
    public function get_notification_count($noti_id){
        $res = array();
        $res['status'] = 1;
        try{
            $sql = "SELECT COUNT(id) FROM notilogs WHERE notiID=:not_id";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':not_id', $noti_id, PDO::PARAM_STR);
            $stmt->execute();
            $res['result'] = $stmt->fetch();
        }catch(PDOException $e)
        {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }

     //////////////////////////////////////////////////////////////////////////////////////
    public function get_assignment_by_CourseID_And_AssName($ass_name,$courseID)
    {
        $res = array();
        $res['status'] = 1;
        try{
            $sql = "SELECT * FROM assignments WHERE name=:as_nm AND coarseID=:cs_id";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':as_nm', $ass_name, PDO::PARAM_INT);
            $stmt->bindvalue(':cs_id', $courseID, PDO::PARAM_STR);
            $stmt->execute();
            $res['result'] = $stmt->fetch();
        }catch(PDOException $e)
        {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
            $Handler = fopen("Error_Log.txt","a");
            fwrite($Handler,date('Y-m-d H:i:s a',time()).' -->'.json_encode($res['report'])."\n");
            fclose($Handler);
        }
        return $res;
    }
    public function get_user_profile_by_UserID($User_ID)
    {
        $res = array();
        $res['status'] = 1;
        try{
            $sql = "SELECT * FROM userprofiles WHERE MobUserId=:us_id";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':us_id', $User_ID, PDO::PARAM_INT);
            $stmt->execute();
            $res['result'] = $stmt->fetch();
        }catch(PDOException $e)
        {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
            $Handler = fopen("Error_Log.txt","a");
            fwrite($Handler,date('Y-m-d H:i:s a',time()).' -->'.json_encode($res['report'])."\n");
            fclose($Handler);
        }
        return $res;
    }
    public function get_submitted_assignments_for_Lec($IMEI)
    {
        $res = array();
        $res['status'] = 1;
        try{
            $user_id = $this->get_user_id($IMEI)['result']['id'];
            $class_id = $this->get_classID_by_userID($user_id)['result']['classID'];
            $sql = "SELECT A.id, A.name, A.lectID, A.coarseID, A.classID, A.filepath, A.submissionDate, A.description,P.fileName, P.seen,P.created FROM assignments as A,posts as P WHERE A.classID=:cls_id AND A.id = P.assignmentID";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':cls_id', $class_id, PDO::PARAM_INT);
            $stmt->execute();
            $data = array();
            while($row = $stmt->fetch())
            {
                $data[] = $row;

            }
            $res['result'] = $data;
        }catch(PDOException $e)
        {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
            $Handler = fopen("Error_Log.txt","a");
            fwrite($Handler,date('Y-m-d H:i:s a',time()).' -->'.json_encode($res['report'])."\n");
            fclose($Handler);
        }
        return $res;
    }

    public function add_new_Chat($User_id, $Body,$Destination,$Scope)
    {
        $res = array();
        $res['status'] = 1;
        try{
            $sql = "INSERT INTO conversations(user_id, body,destination,scope) VALUES (:us_id, :ct_bd, :ct_dt, :ct_sp)";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':us_id', $User_id, PDO::PARAM_INT);
            $stmt->bindvalue(':ct_bd', $Body, PDO::PARAM_STR);
            $stmt->bindvalue(':ct_dt', $Destination, PDO::PARAM_STR);
            $stmt->bindvalue(':ct_sp', $Scope, PDO::PARAM_STR);
            $stmt->execute();
        }catch(PDOException $e)
        {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
            $Handler = fopen("Error_Log.txt","a");
            fwrite($Handler,date('Y-m-d H:i:s a',time()).' -->'.json_encode($res['report'])."\n");
            fclose($Handler);
        }
        return $res;
    }
    public function get_chat_by_id($id)
    {
        $res = array();
        $res['status'] = 1;
        try{
            $sql = "SELECT * FROM conversations WHERE id= :ct_id";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':ct_id', $id, PDO::PARAM_INT);
            $stmt->execute();
            $res['result'] = $stmt->fetch();
        }catch(PDOException $e)
        {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
            $Handler = fopen("Error_Log.txt","a");
            fwrite($Handler,date('Y-m-d H:i:s a',time()).' -->'.json_encode($res['report'])."\n");
            fclose($Handler);
        }
        return $res;
    }
    public function get_all_chats_by_scope($Scope)
    {
        $res = array();
        $res['status'] = 1;
        try{
            $sql = "SELECT * FROM conversations WHERE scope= :ct_sp";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':ct_sp', $Scope, PDO::PARAM_STR);
            $stmt->execute();
            $set = array();
            while($row = $stmt->fetch())
            {
                $set[] = $row;
            }
            $res['result'] = $set;
        }catch (PDOException $e)
        {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
            $Handler = fopen("Error_Log.txt","a");
            fwrite($Handler,date('Y-m-d H:i:s a',time()).' -->'.json_encode($res['report'])."\n");
            fclose($Handler);
        }
        return $res;
    }
    public function get_all_chats_by_destination($destination_id)
    {
        $res = array();
        $res['status'] = 1;
        try{
            $sql = "SELECT * FROM conversations WHERE destination= :ct_sp";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':ct_sp', $destination_id, PDO::PARAM_STR);
            $stmt->execute();
            $set = array();
            while($row = $stmt->fetch())
            {
                $set[] = $row;
            }
            $res['result'] = $set;
        }catch (PDOException $e)
        {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
            $Handler = fopen("Error_Log.txt","a");
            fwrite($Handler,date('Y-m-d H:i:s a',time()).' -->'.json_encode($res['report'])."\n");
            fclose($Handler);
        }
        return $res;
    }
    public function get_all_chats_by_destination_AND_Scope($destination_id,$Scope)
    {
        $res = array();
        $res['status'] = 1;
        try{
            $sql = "SELECT * FROM conversations WHERE destination= :ct_dt AND scope= :ct_sp";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':ct_sp', $Scope, PDO::PARAM_STR);
            $stmt->bindvalue(':ct_dt', $destination_id, PDO::PARAM_INT);
            $stmt->execute();
            $set = array();
            while($row = $stmt->fetch())
            {
                $set[] = $row;
            }
            $res['result'] = $set;
        }catch (PDOException $e)
        {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
            $Handler = fopen("Error_Log.txt","a");
            fwrite($Handler,date('Y-m-d H:i:s a',time()).' -->'.json_encode($res['report'])."\n");
            fclose($Handler);
        }
        return $res;
    }
    public function get_conversation_update($user_id,$destination_id,$Scope)
    {
        $res = array();
        $res['status'] = 1;
        try{
            $sql = "SELECT * FROM conversations WHERE destination= :ct_dt AND scope= :ct_sp AND id NOT IN (SELECT chat_id FROM conversation_logs WHERE user_id=:us_id)";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':ct_sp', $Scope, PDO::PARAM_STR);
            $stmt->bindvalue(':ct_dt', $destination_id, PDO::PARAM_INT);
            $stmt->bindvalue(':us_id', $user_id, PDO::PARAM_INT);
            $stmt->execute();
            $set = array();
            while($row = $stmt->fetch())
            {
                $set[] = $row;
            }
            $res['result'] = $set;
        }catch (PDOException $e)
        {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
            $this->log_error($res['report']);
        }
        return $res;
    }
    public function log_error($error)
    {
        $Handler = fopen("Error_Log.txt","a");
        fwrite($Handler,date('Y-m-d H:i:s a',time()).' -->'.json_encode($error."\n"));
        fclose($Handler);
    }







    public function add_new_Chat_Log($User_id, $chat_id)
    {
        $res = array();
        $res['status'] = 1;
        try{
            $sql = "INSERT INTO conversation_logs(user_id, chat_id) VALUES (:us_id, :ct_id)";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':us_id', $User_id, PDO::PARAM_INT);
            $stmt->bindvalue(':ct_id', $chat_id, PDO::PARAM_INT);
            $stmt->execute();
        }catch(PDOException $e)
        {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();                          $Handler = fopen("Error_Log.txt","a");             fwrite($Handler,date('Y-m-d H:i:s a',time()).' -->'.json_encode($res['report'])."\n");             fclose($Handler);
        }
        return $res;
    }

    ////////////////////////////////////////////////////////////////
    // RSS Site Table
    ////////////////////////////////////
    public function add_new_RSS_Site($User_id,$Host,$Link,$Category,$Type)
    {
        $res = array();
        $res['status'] = 1;
        try{
            $sql = "INSERT INTO rss_sites (user_id,host,link,category,rss_sites.type) VALUES (:rs_id,:rs_ht,:rs_lk,:rs_ct,:rs_tp)";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':rs_id', $User_id, PDO::PARAM_INT);
            $stmt->bindvalue(':rs_ht', $Host, PDO::PARAM_STR);
            $stmt->bindvalue(':rs_lk', $Link, PDO::PARAM_STR);
            $stmt->bindvalue(':rs_ct', $Category, PDO::PARAM_STR);
            $stmt->bindvalue(':rs_tp', $Type, PDO::PARAM_STR);
            $stmt->execute();
        }catch(PDOException $e)
        {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
            $this->log_error($res['report']);
        }
        return $res;
    }
    public function get_All_RSS_Site()
    {
        $res = array();
        $res['status'] = 1;
        try{
            $sql = "SELECT * FROM rss_sites";
            $stmt = $this->connection->prepare($sql);
            $stmt->execute();
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
            $Handler = fopen("Error_Log.txt","a");
            fwrite($Handler,date('Y-m-d H:i:s a',time()).' -->'.json_encode($res['report'])."\n");
            fclose($Handler);
        }
        return $res;
    }
    public function get_RSS_Site_By_ID($ID)
    {
        $res = array();
        $res['status'] = 1;
        try{
            $sql = "SELECT * FROM rss_sites WHERE id=:s_id";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':s_id', $ID, PDO::PARAM_INT);
            $stmt->execute();
            $res['result'] = $stmt->fetch();
        }catch(PDOException $e)
        {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
            $Handler = fopen("Error_Log.txt","a");
            fwrite($Handler,date('Y-m-d H:i:s a',time()).' -->'.json_encode($res['report'])."\n");
            fclose($Handler);
        }
        return $res;
    }
    public function get_RSS_Sites_By_Category($category)
    {
        $res = array();
        $res['status'] = 1;
        try{
            $sql = "SELECT * FROM rss_sites WHERE category= :ct_dt";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':ct_dt', $category, PDO::PARAM_STR);
            $stmt->execute();
            $res['result'] = array();
            $set = array();
            while($row = $stmt->fetch())
            {
                $set[] = $row;
            }
            $res['result'] = $set;
        }catch (PDOException $e)
        {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
            $Handler = fopen("Error_Log.txt","a");
            fwrite($Handler,date('Y-m-d H:i:s a',time()).' -->'.json_encode($res['report'])."\n");
            fclose($Handler);
        }
        return $res;
    }
    public function delete_RSS_Site($ID)
    {
        $res = array();
        $res['status'] = 1;
        try{
            $sql = "DELETE FROM rss_sites WHERE id=:rs_id";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':rs_id', $ID, PDO::PARAM_INT);
            $stmt->execute();
        }catch(PDOException $e)
        {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();

            $Handler = fopen("Error_Log.txt","a");
            fwrite($Handler,date('Y-m-d H:i:s a',time()).' -->'.json_encode($res['report'])."\n");
            fclose($Handler);
        }
        return $res;
    }

    ////////////////////////////////////////////////////////////////
    // RSS Feed Table
    ////////////////////////////////////
    public function add_new_RSS_Feed($RSS_Site_ID,$Title,$Desc,$Pub_Date,$Link,$Img_Link)
    {
        $res = array();
        $res['status'] = 1;
        try{
            $sql = "INSERT INTO rss_feeds (rss_site_id,title,description,pub_date,link,img_link)VALUES (:rs_st,:rs_tl,:rs_dc,:rs_dt,:rs_lk,:rs_im)";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':rs_st', $RSS_Site_ID, PDO::PARAM_INT);
            $stmt->bindvalue(':rs_tl', $Title, PDO::PARAM_STR);
            $stmt->bindvalue(':rs_dc', $Desc, PDO::PARAM_STR);
            $stmt->bindvalue(':rs_dt', $Pub_Date, PDO::PARAM_STR);
            $stmt->bindvalue(':rs_lk', $Link, PDO::PARAM_STR);
            $stmt->bindvalue(':rs_im', $Img_Link, PDO::PARAM_STR);
            $stmt->execute();
        }catch(PDOException $e)
        {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
            $Handler = fopen("Error_Log.txt","a");
            fwrite($Handler,date('Y-m-d H:i:s a',time()).' -->'.json_encode($res['report'])."\n");
            fclose($Handler);
        }
        return $res;
    }
    public function get_RSS_Feed_By_ID($ID)
    {
        $res = array();
        $res['status'] = 1;
        try{
            $sql = "SELECT * FROM rss_feeds WHERE id=:rs_id";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':rs_id', $ID, PDO::PARAM_INT);
            $stmt->execute();
            $res['result'] = $stmt->fetch();
        }catch(PDOException $e)
        {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
            $Handler = fopen("Error_Log.txt","a");
            fwrite($Handler,date('Y-m-d H:i:s a',time()).' -->'.json_encode($res['report'])."\n");
            fclose($Handler);
        }
        return $res;
    }
    public function get_New_RSS_Feed($User_ID)
    {
        $res = array();
        $res['status'] = 1;
        try{
            $sql = "SELECT * FROM rss_feeds WHERE id NOT IN (SELECT rss_feed_id FROM rss_feed_logs WHERE user_id=:us_id)";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':us_id', $User_ID, PDO::PARAM_INT);
            $stmt->execute();
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
            $Handler = fopen("Error_Log.txt","a");
            fwrite($Handler,date('Y-m-d H:i:s a',time()).' -->'.json_encode($res['report'])."\n");
            fclose($Handler);
        }
        return $res;
    }
    public function get_RSS_Feed_By_Category($Category)
    {
        $res = array();
        $res['status'] = 1;
        $res['result'] = array();
        try{
            $Rss_Sites = $this->get_RSS_Sites_By_Category($Category);
            if(isset($Rss_Sites['status']) && $Rss_Sites['status'] == 1 && isset($Rss_Sites['result'])){
                $Rss_Sites = $Rss_Sites['result'];
                foreach($Rss_Sites as $site){
                    $found = $this->get_RSS_Feed_By_ID($site['id']);
                    if (isset($found) && isset($found['result'])) {
                        $res['result'][] = $found['result'];
                    }
                }
            }
        }catch(PDOException $e)
        {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
            $this->log_error($res['report']);
        }
        return $res;
    }

    public function delete_RSS_Feed($ID)
    {
        $res = array();
        $res['status'] = 1;
        try{
            $sql = "DELETE FROM rss_feeds WHERE id=:rs_id";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':rs_id', $ID, PDO::PARAM_INT);
            $stmt->execute();
        }catch(PDOException $e)
        {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
            $Handler = fopen("Error_Log.txt","a");
            fwrite($Handler,date('Y-m-d H:i:s a',time()).' -->'.json_encode($res['report'])."\n");
            fclose($Handler);
        }
        return $res;
    }


    //////////////////////////////////////////////////////////
    // RSS Feeds Log Table
    ////////////////////////////////////////////////////////
    public function add_new_RSS_Logs($User_id,$RSS_Feed_ID)
    {
        $res = array();
        $res['status'] = 1;
        try{
            $sql = "INSERT INTO rss_feed_logs (user_id,rss_feed_id) VALUES (:us_id,:rs_id)";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':us_id', $User_id, PDO::PARAM_INT);
            $stmt->bindvalue(':rs_id', $RSS_Feed_ID, PDO::PARAM_INT);
            $stmt->execute();
        }catch(PDOException $e)
        {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
            $Handler = fopen("Error_Log.txt","a");
            fwrite($Handler,date('Y-m-d H:i:s a',time()).' -->'.json_encode($res['report'])."\n");
            fclose($Handler);
        }
        return $res;
    }
    public function delete_RSS_Feed_Log($ID)
    {
        $res = array();
        $res['status'] = 1;
        try{

            $sql = "DELETE FROM rss_feed_logs WHERE id=:rs_id";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':rs_id', $ID, PDO::PARAM_INT);
            $stmt->execute();
        }catch(PDOException $e)
        {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
            $Handler = fopen("Error_Log.txt","a");
            fwrite($Handler,date('Y-m-d H:i:s a',time()).' -->'.json_encode($res['report'])."\n");
            fclose($Handler);
        }
        return $res;
    }


    ////////////////////////////////////////////////////////////////////////////////////
    // Update News Feeds
    public function Update_News_Feed(){
        $RSS_Sites = $this->get_All_RSS_Site();

        if(isset($RSS_Sites) && isset($RSS_Sites['result'])){
            $RSS_Sites = $RSS_Sites['result'];
            foreach($RSS_Sites as $RSS_SITE){

                $rss = simplexml_load_file($RSS_SITE['link']);
                $News_ID = $RSS_SITE['id'];
                foreach ($rss->channel->item as $item) {
                    $Title = $item->title;
                    $Description = $item->description;
                    $Pub_Date = $item->pubDate;
                    $Link = $item->link;
                    $Img_Link = $item->children('content')->content->attributes()->url;
                    $this->add_new_RSS_Feed($News_ID,$Title,$Description,$Pub_Date,$Link,$Img_Link);
                }
            }
        }

    }
     ////////////////////////////////////////////////////////////
    public function is_active_user($username, $password)
    {
        $res = array();
        $res['status'] = 1;
        $res['result'] = null;
        try {
            $sql = "SELECT id, username, active FROM users WHERE password=password(:pass)";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':pass', $password, PDO::PARAM_STR);
            $stmt->execute();
            while ($row = $stmt->fetch()) {
                if ($row['username'] === $username && $row['active'] == 1) {
                    $res['result'] = 1;
                    break;
                }
            }
        } catch (PDOException $e) {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }

    public function send_SMS($MSG_To,$MSG_Body){
        require_once("/SMS/Aleka_SMS/send_sms.php");
        Send_Message($MSG_To,$MSG_Body);
    }
    
    public function Send_SMS_By_Class($Class_ID,$Message){
        $res = array();
        $res['status'] = 1;
        $res['result'] = null;

        try {
            $sql = "SELECT * FROM userprofiles WHERE classID=:cl_id AND MobUserID IN (SELECT id FROM users WHERE IMEI is null)";
            $stmt = $this->connection->prepare($sql);
            $stmt->bindvalue(':cl_id', $Class_ID, PDO::PARAM_INT);
            $stmt->execute();
            while ($row = $stmt->fetch()) {
                //
                //
                $this->send_SMS($row['phone'],$Message);
                $i = 10000000;
                while($i > 0)
                {
                    $i--;
                }
            }

        } catch (PDOException $e) {
            $res['status'] = 0;
            $res['report'] = $e->getMessage();
        }
        return $res;
    }
}
?>