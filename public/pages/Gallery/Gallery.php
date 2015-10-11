<br><br>
<?php
function get_Materials_by_grade_and_category($grade_id,$Cat){
    $My_DB = new My_Database();
    $found = "";
    if(isset($My_DB->get_materials_by_grade_and_category($grade_id,$Cat)['result'])){
        $Materials = $My_DB->get_materials_by_grade_and_category($grade_id,$Cat)['result'];
        foreach($Materials as $material){
            $file_path = '../private/videos/'.$material['file_name'];
            $bb = '?page=p_gallery&amp;file_path='.$file_path.'&amp;grade='.$material['grade_id'];
            $found = $found.'<li><a href='.$bb.'>'.$material['name'].'</a> </li>';
        }
        $found = '<ul>'.$found.'</ul>';
    }
    return $found;
}
function get_Materials_by_grade_and_subject_and_category($grade_id,$subject_id,$cat){
    $My_DB = new My_Database();
    $found = "";
    if(isset($My_DB->get_materials_by_grade_and_subject_and_category($grade_id,$subject_id,$cat)['result'])){
        $Materials = $My_DB->get_materials_by_grade_and_subject_and_category($grade_id,$subject_id,$cat)['result'];
        foreach($Materials as $material){
            $file_path = '../private/videos/'.$material['file_name'];
            $bb = '?page=p_gallery&amp;file_path='.$file_path.'&amp;grade='.$material['grade_id'].'&amp;subject='.$material['subject_id'];
            $found = $found.'<li><a href='.$bb.'>'.$material['name'].'</a> </li>';
        }
        $found = '<ul>'.$found.'</ul>';
    }
    return $found;
}
function get_Material_Lists_Thm($file_name){
    $My_DB = new My_Database();
    $subj_ID = $My_DB->get_material_by_file_name($file_name)['result']['sub_id'];
    $Cat = $My_DB->get_material_by_file_name($file_name)['result']['category'];
    $found = "";
    if(isset($My_DB->get_material_by_subject_And_Category($subj_ID,$Cat)['result'])){
        $Materials = $My_DB->get_material_by_subject_And_Category($subj_ID,$Cat)['result'];
        foreach($Materials as $material){
            $bb = '?page=tuts&amp;file_path='.$material['file_name'].'&amp;subj='.$subj_ID;
            $found = $found. '<div class="col-sm-12 pull-left" style="border-bottom: 1px dashed #000000"><img src="images/SJS LOGO.PNG" <small><a href='.$bb.'>'.$material['title'].'</a></small></div>';
        }
    }
    return $found;
}
function get_Categories_by_grade($grade_id,$subject_id=null){
    $My_DB = new My_Database();
    $found = "";
    if($subject_id == null){
        if(isset($My_DB->get_material_category_for_grade($grade_id)['result'])){
            $Materials = $My_DB->get_material_category_for_grade($grade_id)['result'];
            foreach($Materials as $material){
                $found = $found.'<li><a href="#"><strong  style="color: dimgray">'.$material['category'].'</strong></a>'.get_Materials_by_grade_and_category($grade_id,$material['category']).'</li>';
            }
            $found = '<ul>'.$found.'</ul>';
        }
    }else{
        if(isset($My_DB->get_material_category_for_grade_and_subject($grade_id,$subject_id)['result'])){
            $Materials = $My_DB->get_material_category_for_grade_and_subject($grade_id,$subject_id)['result'];
            foreach($Materials as $material){
                $found = $found.'<li><a href="#"><strong  style="color: dimgray">'.$material['category'].'</strong></a>'.get_Materials_by_grade_and_subject_and_category($grade_id,$subject_id,$material['category']).'</li>';
            }
            $found = '<ul>'.$found.'</ul>';
        }
    }

    return $found;
}
$tut_path = '';
if (isset($_GET)){
    if(isset($_GET['tut'])){
        $tut_path = 'videos/'.$_GET['tut'];
    }
}
?>
<div class="col-sm-12">

</div>
<div class="container">
    <div class="col-sm-12" style="text-align: center">
        <div  style="align-content: center">
            <?php
            $name = $My_DB->get_grade_by_id($_GET['grade'])['result'];
            ?>
            <h3><?php echo $name['name']?> Educational Videos</h3>
        </div>
        <hr>

    </div>
</div>
<div  class="col-sm-12">
    <div class="row">
        <div class="col-sm-3">
            <form method="GET"action="" style="position: relative; left:50px">
                <span class="col-sm-8">
                    <label class="label label-info"> Grade  </label>
                    <input hidden="hidden" type="text" value="p_gallery" name="page">
                    <input hidden="hidden" type="text" value="<?php echo$_GET['grade']; ?>" name="grade">
                    <select class="form-control" data-placeholder="Choose Type" name="subject">

                        <?php
                        $unique = array();
                        if(isset($_GET['subject'])){
                            $name = $My_DB->get_subject_by_id($_GET['subject'])['result']['name'];
                            $sub_id = $My_DB->get_subject_by_id($_GET['subject'])['result']['id'];
                            array_push($unique,$subject['subject_id']);
                            echo '<option  value='.$sub_id.'>'.$name.'</option>';
                        }
                        $grade = $_GET['grade'];
                        $subjects = $My_DB->get_materials_by_grade($grade)['result'];

                        foreach($subjects as $subject){
                            if(!in_array($subject['subject_id'],$unique)){
                                array_push($unique,$subject['subject_id']);
                                $name = $My_DB->get_subject_by_id($subject['subject_id'])['result']['name'];
                                $sub_id = $My_DB->get_subject_by_id($subject['subject_id'])['result']['id'];
                                echo '<option  value='.$sub_id.'>'.$name.'</option>';
                            }

                        }
                        ?>
                    </select>
                </span>
                <span class="col-sm-3">
                    <label class="label label-info"> </label>
                    <input class="form-control" type="submit" value="Go">
                </span>
            </form>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-3" style="margin-top: 30px; margin-left: 20px; border-right: 2px solid #000000">
        <div class="well">
            <ul class="nav">
                <?php
                $p_subject = null;
                if(isset($_GET['subject'])){
                    $p_subject = $_GET['subject'];
                }
                $choose_list = get_Categories_by_grade($_GET['grade'],$p_subject);
                echo $choose_list;
                ?>
            </ul>
        </div>
    </div>
    <div class="col-sm-8">
        <form method="post" action="">
            <div class="col-sm-2">
                <?php
                $file_path = '';
                if(isset($_GET['file_path'])){
                    $file_path = $_GET['file_path'];
                }
                ?>
                <video  src="<?php echo $file_path;?>" autoplay="autoplay" controls="controls" loop="loop" width="800" height="400"></video>
            </div>

        </form>
    </div>
</div>
<br><br><br>
<script type="text/javascript">
    $(document).ready(function() {
        $('.nav').navgoco();
    });
</script>