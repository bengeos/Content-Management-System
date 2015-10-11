<?php
function upload_file($file){
    $target_dir = '../private/videos/';
    $path_parts = pathinfo($file["name"]);
    $extension = $path_parts['extension'];
    $file_name = get_rand().'.'.$extension;
    $target_file = $target_dir . $file_name;
    $response = "";
    $size = $file['size'];
    if($size !==false){
        if(move_uploaded_file($file['tmp_name'],$target_file)){
            $response = $file_name;
        }
    }
    return $response;
}
function get_rand(){
    $const = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    $data = "";
    for($i=0;$i<10;$i++){
        $data = $data.$const[rand(0,35)];
    }
    $data = md5($data.time());
    return $data;
}
?>