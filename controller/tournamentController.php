<?php
session_start();
require_once('../model/tournamentModel.php');

if (isset($_POST['submit'])) {
    $title = trim($_POST['title']);
    $category = $_POST['category'];
    $description = trim($_POST['content']);
    $username = $_SESSION['username'];

    // ১. ব্যানার আপলোড (Item 20)
    $banner = $_FILES['attachment']; // ব্যানার ইনপুট
    $bannerName = "";
    if (!empty($banner['name'])) {
        $bannerExt = strtolower(pathinfo($banner['name'], PATHINFO_EXTENSION));
        $bannerName = "banner_" . time() . "." . $bannerExt;
        move_uploaded_file($banner['tmp_name'], '../uploads/banners/' . $bannerName);
    }

    $tournament = [
        'title' => $title,
        'category' => $category,
        'description' => $description,
        'banner_image' => $bannerName,
        'created_by' => $username
    ];

    $t_id = createTournament($tournament);

    if ($t_id) {
        // ২. অতিরিক্ত ডকুমেন্ট আপলোড (Item 21 - Rules/PDF)
        if (!empty($_FILES['rulebook']['name'])) {
            $doc = $_FILES['rulebook'];
            $docExt = strtolower(pathinfo($doc['name'], PATHINFO_EXTENSION));
            $docName = "rule_" . time() . "." . $docExt;
            $docPath = '../uploads/docs/' . $docName;

            if (move_uploaded_file($doc['tmp_name'], $docPath)) {
                addAttachment($t_id, $doc['name'], $docName, $docExt);
            }
        }
        logActivity("Tournament created with attachments: $title");
        header('location: ../view/tournamentList.php?success=created');
    } else {
        header('location: ../view/createTournament.php?error=db_error');
    }
}
?>