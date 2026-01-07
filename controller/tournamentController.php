<?php
session_start();
require_once('../model/tournamentModel.php');
if (isset($_POST['submit'])) {
    $title = trim($_POST['title']);
    $category = $_POST['category'];
    $description = trim($_POST['content']);
    $banner = $_FILES['attachment'];
    $bannerName = "";
    if (!empty($banner['name'])) {
        $bannerExt = strtolower(pathinfo($banner['name'], PATHINFO_EXTENSION));
        $bannerName = "banner_" . time() . "." . $bannerExt;
        move_uploaded_file($banner['tmp_name'], '../uploads/banners/' . $bannerName);
    }
    $tournament = ['title' => $title, 'category' => $category, 'description' => $description, 'banner_image' => $bannerName, 'created_by' => $_SESSION['username']];
    $t_id = createTournament($tournament);
    if ($t_id) {
        if (!empty($_FILES['rulebook']['name'])) {
            $doc = $_FILES['rulebook'];
            $docExt = strtolower(pathinfo($doc['name'], PATHINFO_EXTENSION));
            $docName = "rule_" . time() . "." . $docExt;
            move_uploaded_file($doc['tmp_name'], '../uploads/docs/' . $docName);
            addAttachment($t_id, $doc['name'], $docName, $docExt);
        }
        logActivity("Tournament created: $title");
        header('location: ../view/tournamentList.php?success=created');
    } else {
        header('location: ../view/createTournament.php?error=db_error');
    }
}
if (isset($_POST['update'])) {
    $tournament = ['id' => $_POST['id'], 'title' => trim($_POST['title']), 'category' => $_POST['category'], 'description' => trim($_POST['description']), 'status' => $_POST['status']];
    if (updateTournament($tournament)) {
        header('location: ../view/tournamentList.php?success=updated');
    }
}
?>