<?php
//upload.php
$base_url = 'http://localhost/taxi/public';
$rand = rand(1, 9999);
if (isset($_POST["image"])) {
    $data = $_POST["image"];

    $image_array_1 = explode(";", $data);

    $image_array_2 = explode(",", $image_array_1[1]);

    $data = base64_decode($image_array_2[1]);

    $imageName = ((isset($_GET['title'])) ? $_GET['title'] : time()) . '.jpg';

    file_put_contents('../storage/drivers/' . $imageName, $data);

    echo "<img src='" . $base_url . "/storage/drivers/" . $imageName . "?r=" . $rand . "' class='img-thumbnail' />";
}