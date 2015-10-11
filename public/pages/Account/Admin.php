<?php
if(isset($_GET['add_new_subject'])){
    if(strlen($_GET['subject_name'])>2){
        $My_DB->add_subject(1,$_GET['subject_name']);
    }
}
if(isset($_GET['add_new_grade'])){
    if(strlen($_GET['grade_name'])>2){
        $My_DB->add_grade(1,$_GET['grade_name']);
    }
}
if(isset($_GET['_subject_id'])){
    $My_DB->remove_subject($_GET['_subject_id']);
}
if(isset($_GET['_grade_id'])){
    $My_DB->remove_grade($_GET['_grade_id']);
}
if(isset($_GET['_user_type_id'])){
    $pr_type = $My_DB->get_user_by_id($_GET['_user_type_id'])['result']['user_type'];
    $nw_type = 'Admin';
    if($pr_type == 'Admin'){
        $nw_type = 'User';
    }
    $My_DB->update_user_type($_GET['_user_type_id'],$nw_type);
}
if(isset($_GET['_privilege_id'])){
    $pr_type = $My_DB->get_user_by_id($_GET['_privilege_id'])['result']['privilage'];
    $nw_type = '0';
    if($pr_type == '0'){
        $nw_type = '1';
    }
    $My_DB->update_user_privilege($_GET['_privilege_id'],$nw_type);
}
if(isset($_GET['_user_id'])){
    $My_DB->remove_user($_GET['_user_id']);
}
?>
<div class="container">

    <div class="col-sm-12">
        <br><br><br>
        <h2>Admin Page</h2>
        <div class="col-sm-3">
            <table class="table table-striped table-bordered table-responsive table table-hover table table-condensed">
                <thead class="active" style="font-style: normal; background: blanchedalmond">
                <tr>
                    <td><strong>Subject Name</strong></td>
                </tr>
                </thead>
                <tbody style="font-size: 14px">
                <?php
                $Subjects = $My_DB->get_subjects();
                if (isset($Subjects) && isset($Subjects['result'])) {
                    $Subjects = $Subjects['result'];
                    foreach ($Subjects as $data) {
                        echo '<tr></tr><td>' . $data['name'] . '</td>';
                        $path = '?page=u_login&user_name=ben&user_pass=ben&amp;_subject_id='.$data['id'];
                        echo '<td><a href="'.$path.'" class="btn-small" data-toggle="modal" role="button"> <span class="glyphicon glyphicon-remove"></span> Delete </a> </td></tr>';
                    }
                }
                ?>
                <form method="GET" action="" role="form">
                    <input hidden="hidden" type="text" name="page" value="u_login">
                    <input hidden="hidden" type="text" name="user_name" value="<?php echo $_GET['user_name'];?>">
                    <input hidden="hidden" type="text" name="user_pass" value="<?php echo $_GET['user_pass'];?>">
                    <td><input class="form-control" name="subject_name" type="text"></td>
                    <td><input class="btn btn-small" type="submit" name="add_new_subject" value="Add New"> </td>
                </form>

                </tbody>
            </table>
            <hr>
            <table class="table table-striped table-bordered table-responsive table table-hover table table-condensed">
                <thead class="active" style="font-style: normal; background: blanchedalmond">
                <tr>
                    <td><strong>Grade Name</strong></td>
                </tr>
                </thead>
                <tbody style="font-size: 14px">
                <?php
                $Grades = $My_DB->get_grades();
                if (isset($Grades) && isset($Grades['result'])) {
                    $Grades = $Grades['result'];
                    foreach ($Grades as $data) {
                        echo '<tr></tr><td>' . $data['name'] . '</td>';
                        $path = '?page=u_login&user_name=ben&user_pass=ben&amp;_grade_id='.$data['id'];
                        echo '<td><a href="'.$path.'" class="btn-small" data-toggle="modal" role="button"> <span class="glyphicon glyphicon-remove"></span> Delete </a> </td></tr>';
                    }
                }
                ?>
                <form method="GET" action="" role="form">
                    <input hidden="hidden" type="text" name="page" value="u_login">
                    <input hidden="hidden" type="text" name="user_name" value="<?php echo $_GET['user_name'];?>">
                    <input hidden="hidden" type="text" name="user_pass" value="<?php echo $_GET['user_pass'];?>">
                    <td><input class="form-control" name="grade_name" type="text"></td>
                    <td><input class="btn btn-small" type="submit" name="add_new_grade" value="Add New"> </td>
                </form>

                </tbody>
            </table>
        </div>
        <div class="col-sm-9">
            <table class="table table-striped table-bordered table-responsive table table-hover table table-condensed">
                <thead class="active" style="font-style: normal; background: blanchedalmond;font-size: 15px">
                <tr style="font-style: normal">
                    <td><strong>Full Name</strong></td>
                    <td><strong>User Name</strong></td>
                    <td><strong>User Type</strong></td>
                    <td><strong>Privilege</strong></td>
                </tr>
                </thead>
                <tbody style="font-size: 15px">
                <?php
                $users = $My_DB->get_users();
                if (isset($users) && isset($users['result'])) {
                    $users = $users['result'];
                    foreach ($users as $data) {
                        echo '<td>' . $data['full_name'] . '</td>';
                        echo '<td>' . $data['user_name'] . '</td>';
                        $priv = 'Inactive';
                        if($data['privilage'] == '0'){
                            $priv = 'Active';
                        }
                        $change_user_type = '?page=u_login&user_name='.$_GET['user_name'].'&user_pass='.$_GET['user_pass'].'&amp;_user_type_id='.$data['id'];
                        $change_privilege = '?page=u_login&user_name='.$_GET['user_name'].'&user_pass='.$_GET['user_pass'].'&amp;_privilege_id='.$data['id'];
                        $remove_user= '?page=u_login&user_name='.$_GET['user_name'].'&user_pass='.$_GET['user_pass'].'&amp;_user_id='.$data['id'];
                        echo '<td><a href="'.$change_user_type.'" class="btn-small" data-toggle="modal" role="button"> <span class="glyphicon glyphicon-remove"></span> '.$data['user_type'].' </a> </td>';
                        echo '<td><a href="'.$change_privilege.'" class="btn-small" data-toggle="modal" role="button"> <span class="glyphicon glyphicon-remove"></span> '.$priv.' </a> </td>';
                        echo '<td><a href="'.$remove_user.'" class="btn-small" data-toggle="modal" role="button"> <span class="glyphicon glyphicon-remove"></span> Remove User </a> </td></tr>';
                    }
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>
    <br><br><br><br><br><br><br><br><br><br><br><br>
</div>