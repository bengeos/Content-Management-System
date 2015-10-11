<br><br><br><br>
<?php
require_once '../private/API.php';
$API = new API();
function get_Material_Lists($subj_ID,$Cat){
    $API = new API();
    $found = "";
    if(isset($API->get_material_by_subject_And_Category($subj_ID,$Cat)['result'])){
        $Materials = $API->get_material_by_subject_And_Category($subj_ID,$Cat)['result'];
        foreach($Materials as $material){
            $bb = '?page=tuts&amp;file_path='.$material['file_name'].'&amp;subj='.$subj_ID;
            $found = $found.'<li><a href='.$bb.'>'.$material['title'].'</a> </li>';
        }
        $found = '<ul>'.$found.'</ul>';
    }
    return $found;
}
function get_Material_Lists_Thm($file_name){
    $API = new API();
    $subj_ID = $API->get_material_by_file_name($file_name)['result']['sub_id'];
    $Cat = $API->get_material_by_file_name($file_name)['result']['category'];
    $found = "";
    if(isset($API->get_material_by_subject_And_Category($subj_ID,$Cat)['result'])){
        $Materials = $API->get_material_by_subject_And_Category($subj_ID,$Cat)['result'];
        foreach($Materials as $material){
            $bb = '?page=tuts&amp;file_path='.$material['file_name'].'&amp;subj='.$subj_ID;
            $found = $found. '<div class="col-sm-12 pull-left" style="border-bottom: 1px dashed #000000"><img src="images/SJS LOGO.PNG" <small><a href='.$bb.'>'.$material['title'].'</a></small></div>';
        }
    }
    return $found;
}
function get_Categories($subj_ID,$Types){
    $API = new API();
    $found = "";
    if(isset($API->get_all_category_for_subject($subj_ID,$Types)['result'])){
        $Materials = $API->get_all_category_for_subject($subj_ID,$Types)['result'];
        foreach($Materials as $material){
            $found = $found.'<li><a href="#"><strong  style="color: dimgray">'.$material['category'].'</strong></a>'.get_Material_Lists($subj_ID,$material['category']).'</li>';
        }
        $found = '<ul>'.$found.'</ul>';
    }
    return $found;
}
if(isset($_GET['file_path'])){
    $file_path = '../private/files/'.$_GET['file_path'];
}else{
    $file_path = "";
}
print_r($_POST);
if(isset($_POST['tut_selection'])){
    setcookie('tut_grade',$_POST['tut_grade'],(time()+(60*60*24)));
    setcookie('tut_type',$_POST['tut_type'],(time()+(60*60*24)));
}
?>
<div  class="col-sm-12">
    <div class="row">
        <form class="form-group" method="post" action=""> <div class="col-sm-2" style="margin-left: 45px">
                <select class="form-control" name="tut_grade">
                    <?php
                    $grades = $API->get_all_grades()['result'];
                    if(isset($_POST['tut_grade'])){
                        echo '<option value='.$_POST['tut_grade'].'>Grade '.$_POST['tut_grade'].'</option>';
                    }
                    foreach($grades as $grade){
                        echo '<option value='.$grade['grade'].'>Grade '.$grade['grade'].'</option>';
                    }
                    ?>
                </select> </div>
            <div class="col-sm-2"></div>
            <div class="col-sm-2">
                <select class="form-control" data-placeholder="Choose Type" name="tut_type">
                    <?php
                    if(isset($_POST['tut_type'])){
                        echo '<option value='.$_POST['tut_type'].'>'.$_POST['tut_type'].'</option>';
                    }
                    ?>
                    <option  value="Video"> Video </option>
                    <option  value="Simulation"> Simulation </option>
                    <option  value="book"> Book  </option>
                </select>
            </div>
            <div class="col-sm-1">
                <input type="submit"  class="btn btn-default" name="tut_selection" value="GO">
            </div>
        </form>

    </div>

</div>

<div class="row">
    <div class="col-sm-2" style="margin-top: 30px; border-right: 1px solid #000000">
        <div class="well">
            <ul class="nav">
                <?php
                if(isset($_POST['tut_grade'])){
                    $subjects = $API->get_all_Subjects_by_grade($_POST['tut_grade'])['result'];
                    setcookie("tut_grade",$_POST['tut_grade']);
                    setcookie("tut_type",$_POST['tut_type']);
                }elseif(isset($_COOKIE['tut_grade'])){
                    $subjects = $API->get_all_Subjects_by_grade($_COOKIE['tut_grade'])['result'];
                }else{
                    $subjects = $API->get_all_Subjects()['result'];
                }
                foreach($subjects as $subject){
                    if(isset($_POST['tut_type'])){
                        $Type = $_POST['tut_type'];
                    }elseif(isset($_COOKIE['tut_type'])){
                        $Type = $_COOKIE['tut_type'];
                    }else{
                        $Type = 'Video';
                    }
                    $categories = get_Categories($subject['id'],$Type);
                    echo '<li><a href="#">'.$subject['name'].'</a>'.$categories.'</li>';
                }
                ?>
            </ul>
        </div>
    </div>

    <hr>
    <div class="col-sm-5 " style="margin-top: 60px">
        <?php
        if(isset($_POST['tut_type']) && $_POST['tut_type'] == 'Simulation'){
            include_once 'Sims/geometric-optics/geometric-optics_en.html';
        }else{
            echo '<video  src='. $file_path.' autoplay="autoplay" controls="controls" loop="loop" width="700" height="360"></video>';
        }
        ?>

    </div>
    <div class="col-sm-4 well pull-right" style="margin-top: 60px">
        <?php
        if(isset($_GET['file_path'])){
            $LIsts = get_Material_Lists_Thm($_GET['file_path']);
            echo $LIsts;
        }
        ?>
    </div>
</div>

<div class="col-sm-8">
    <form method="post" action="">
        <div class="col-sm-2">   </div>

    </form>
</div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $('.nav').navgoco();
    });
</script>


