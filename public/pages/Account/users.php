<?php
$user = $My_DB->get_user_by_user_name($_GET['user_name'])['result'];
if(isset($_POST['Add_Material'])){
    require_once '../private/File_Manager.php';
    $file = $_FILES['upload_file'];
    $uploaded = upload_file($file);
    if(strlen($uploaded) > 10){
        $ff = $My_DB->add_material($user['id'],$_POST['Title'],$_POST['Grade_id'],$_POST['Subject_id'],$_POST['Category'],$uploaded,$_POST['description']);
    }
}

?>

<div class="container">
    <div class="row">
    <div class="col-sm-12">
        <br><br>
        <h2>User Page</h2>
        <hr>
        <div class="col-sm-9">
            <div>
                <div class="col-sm-3" style="text-align: center">
                   <?php
                   if(isset($_GET['_edit_material'])){
                       $mat_id = $My_DB->get_material_by_id($_GET['_edit_material'])['result'];
                       $tut_path = '../private/videos/'.$mat_id['file_name'];
                       echo "<video  src=".$tut_path." autoplay='autoplay' controls='controls' loop='loop' width='600' height='160'></video>";
                   }
                   ?>



                </div>
            </div>
            <table class="table table-striped table-bordered table-responsive table table-hover table table-condensed">
                <thead class="active" style="font-style: normal; background: blanchedalmond;font-size: 15px">
                <tr style="font-style: normal">
                    <td><strong>Grade</strong></td>
                    <td><strong>Subject</strong></td>
                    <td><strong>Category</strong></td>
                    <td><strong>File Name</strong></td>
                </tr>
                </thead>
                <tbody style="font-size: 15px">
                <?php
                $users = $My_DB->get_materials_by_user_id($user['id']);
                if (isset($users) && isset($users['result'])) {
                    $users = $users['result'];
                    foreach ($users as $data) {
                        $change_privilege = '?page=u_login&user_name='.$_GET['user_name'].'&user_pass='.$_GET['user_pass'].'&amp;_edit_material='.$data['id'];
                        $remove_user= '?page=u_login&user_name='.$_GET['user_name'].'&user_pass='.$_GET['user_pass'].'&amp;_material_id='.$data['id'];
                        $grade_name = $My_DB->get_grade_by_id($data['grade_id'])['result'];
                        $sub_name = $My_DB->get_subject_by_id($data['subject_id'])['result'];
                        $sub_name = $My_DB->get_subject_by_id($data['subject_id'])['result'];

                        if(isset($_GET['_edit_material']) && $_GET['_edit_material'] ==$data['id'] ){
                            $remove_user= '?page=u_login&user_name='.$_GET['user_name'].'&user_pass='.$_GET['user_pass'];





                            echo '<form class="form-control" method="GET">';
                            echo '<input hidden="hidden" type="text" name="user_name" value='.$_GET['user_name'].'>';
                            echo '<input hidden="hidden" type="text" name="user_pass" value='.$_GET['user_pass'].'>';
                            echo '<input hidden="hidden" type="text" name="page" value="user_page">';
                            echo '<td><select class="form-control" name="Grade_id">';
                            $subjects = $My_DB->get_grades()['result'];
                            $gades = array();
                            foreach($subjects as $subject){
                                if(!in_array($subject['name'],$gades)){
                                    array_push($gades,$subject['name']);
                                    echo '<option value='.$subject['id'].'>'.$subject['name'].'</option>';
                                }

                            }
                            echo '</select></td>';
                            echo '<td><select class="form-control" name="Subject_id">';
                            $subjects = $My_DB->get_subjects()['result'];
                            $subj = array();
                            foreach($subjects as $subject){
                                if(!in_array($subject['name'],$subj)){

                                    array_push($subj,$subject['name']);
                                    echo '<option value='.$subject['id'].'>'.$subject['name'].'</option>';
                                }

                            }
                            echo '</select></td>';
                            echo '<td><input class="form-control" type="text" name="Category_" value='.$data['category'].'></td>';
                            echo '<td>' . $data['name'] . '</td>';
                            echo '<td><input class="form-control" type="submit" name="Save_Changes" value="Save"></td>';
                            echo '<td><a href="'.$remove_user.'" class="btn-small" data-toggle="modal" role="button"> <span class="glyphicon glyphicon-remove"></span>Cancel</a> </td></tr>';
                            echo '</form>';
                        }else{
                            echo '<td>' . $grade_name['name'] . '</td>';
                            echo '<td>' . $sub_name['name'] . '</td>';
                            echo '<td>' . $data['category'] . '</td>';
                            echo '<td>' . $data['name'] . '</td>';
                            echo '<td><a href="'.$change_privilege.'" class="btn-small" data-toggle="modal" role="button"> <span class="glyphicon glyphicon-edit"></span>Edit</a> </td>';
                            echo '<td><a href="'.$remove_user.'" class="btn-small" data-toggle="modal" role="button"> <span class="glyphicon glyphicon-remove"></span> Remove File </a> </td></tr>';
                        }



                    }
                }
                ?>
                </tbody>
            </table>
        </div>
        <span class="col-sm-3">
                <h4><u>Add a course material</u></h4>
                        <form action="" method="POST" enctype="multipart/form-data">
                            <input hidden="hidden" type="text" name="page" value="user_page">
                            <input hidden="hidden" type="text" name="user_name" value="<?php echo $_GET['user_name'];?>">
                            <input hidden="hidden" type="text" name="user_pass" value="<?php echo $_GET['user_pass'];?>">
                            <label class="label label-info">Title</label>
                            <input class="form-control" name="Title" placeholder="Title Of the File">
                            <label class="label label-info">Subject</label>
                            <select class="form-control" name="Subject_id">
                                <?php
                                $subjects = $My_DB->get_subjects()['result'];
                                foreach($subjects as $subject){
                                    echo '<option value='.$subject['id'].'>'.$subject['name'].'</option>';
                                }
                                ?>
                            </select>
                            <label class="label label-info">Grade</label>
                            <select class="form-control" name="Grade_id">
                                <?php
                                $subjects = $My_DB->get_grades()['result'];
                                foreach($subjects as $subject){
                                    echo '<option value='.$subject['id'].'>'.$subject['name'].'</option>';

                                }
                                ?>
                            </select>
                            <label class="label label-info">Category</label>
                            <input class="form-control" name="Category" placeholder="Chapter">
                            <br>
                            <label class="label label-info">Description</label>
                            <input class="form-control" type="text" name="description" placeholder="Chapter">
                            <br>
                            <input class="form-control" type="file" name="upload_file" value="Add Material">
                            <br>
                            <input class="form-control" type="submit" name="Add_Material" value="Add Material">
                        </form>
            </span>
    </div>
    </div>


    <br><br><br><br><br><br><br><br><br><br><br><br>
</div>