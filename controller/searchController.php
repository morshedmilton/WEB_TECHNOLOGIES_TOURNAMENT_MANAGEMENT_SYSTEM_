<?php
require_once('../model/db.php');

if (isset($_POST['search_data'])) {

    $json = $_POST['search_data'];
    $obj = json_decode($json);

    $con = getConnection();
    $query = mysqli_real_escape_string($con, $obj->query);

    $sql = "select * from tournaments where title like '%$query%' or category like '%$query%'";
    $result = mysqli_query($con, $sql);

    $tournaments = [];
    if (mysqli_num_rows($result) > 0) {
        while ($t = mysqli_fetch_assoc($result)) {
            $tournaments[] = $t;
        }
    }

    echo json_encode($tournaments);
    mysqli_close($con);
}
?>