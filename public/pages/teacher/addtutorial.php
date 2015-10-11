<?php
require_once '../private/API.php';
$API = new API();
if(isset($_POST['Add_Material'])){
    require_once '../private/File_Manager.php';
    $file = $_FILES['upload_file'];
    $uploaded = upload_file($file);
    if(strlen($uploaded) > 10){
        $Sub_ID = $API->get_subject_by_Subj_AND_Grade($_POST['Subject'],$_POST['Grade'])['result']['id'];
        $API->add_new_material(1,$Sub_ID,$_POST['Title'],$_POST['Category'],$_POST['Type'],$uploaded);
    }
}
if(isset($_POST['Remove_Material'])){
    $API->delete_material($_POST['material_id']);
}
?>
<br>
<div class="container">
    <div class="row">
        <div class="col-sm-12">
                <div class="col-sm-8">
                    <hr>
                    <table class="table table-bordered ">
                        <thead>
                        <tr style="background: #ececec">
                            <th>Title</th>
                            <th>Subject</th>
                            <th>Grade</th>
                            <th>Category</th>
                            <th>Type</th>
                        </tr>
                        </thead class="table table-condensed">
                        <tbody >
                        <?php
                        if(isset($API->get_material_by_userID(1)['result'])){
                            $Founds = $API->get_material_by_userID(1)['result'];
                            $Founds = array_reverse($Founds);
                            foreach($Founds as $found){
                                $found['subject'] = $API->get_subject_by_subID($found['sub_id'])['result']['name'];
                                $found['grade'] = $API->get_subject_by_subID($found['sub_id'])['result']['grade'];
                                include 'pages/teacher/added_materials_table_list.php';
                            }
                        }
                        ?>
                        </tbody>
                    </table>
                </div>
                <div class="col-sm-3">
                    <div class="col-sm-12">
                        <h4><u>Add a course material</u></h4>
                        <form action="" method="post" enctype="multipart/form-data">
                            <label class="label label-info">Title</label>
                            <input class="form-control" name="Title" placeholder="Title Of the File">
                            <label class="label label-info">Material Type</label>
                            <select class="form-control" name="Type">
                                <option>Video</option>
                                <option>Photo</option>
                                <option>Simulation</option>
                                <option>Links</option>
                            </select>
                            <label class="label label-info">Subject</label>
                            <select class="form-control" name="Subject">
                                <?php
                                $subjects = $API->get_all_Subjects()['result'];
                                foreach($subjects as $subject){
                                    echo '<option value='.$subject['name'].'>'.$subject['name'].'</option>';
                                }
                                ?>
                            </select>
                            <label class="label label-info">Grade</label>
                            <select class="form-control" name="Grade">
                                <?php
                                $subjects = $API->get_all_grades()['result'];
                                foreach($subjects as $subject){
                                    echo '<option value='.$subject['grade'].'> Grade '.$subject['grade'].'</option>';
                                }
                                ?>
                            </select>
                            <label class="label label-info">Category</label>
                            <input class="form-control" name="Category" placeholder="Chapter">
                            <br>
                            <input class="form-control" type="file" name="upload_file" value="Add Material">
                            <br>
                            <input class="form-control" type="submit" name="Add_Material" value="Add Material">
                        </form>
                    </div>
                    <div class="col-sm-9">
                        <table class="table table">

                        </table>
                    </div>
                </div>

        </div>
    </div>

</div>