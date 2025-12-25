<?php
require_once('../model/db.php');

if (isset($_GET['query'])) {
    $query = $_GET['query'];
    $con = getConnection();

    // Search by tournament title
    $sql = "select * from tournaments where title like '%$query%'";
    $result = mysqli_query($con, $sql);

    if (mysqli_num_rows($result) > 0) {
        echo "<table border='1' cellspacing='0' cellpadding='10' style='width: 100%; text-align: center;'>
                <tr style='background-color: #f2f2f2;'>
                    <th>ID</th><th>Title</th><th>Category</th><th>Status</th><th>Actions</th>
                </tr>";
        while ($t = mysqli_fetch_assoc($result)) {
            echo "<tr>
                    <td>{$t['id']}</td>
                    <td>{$t['title']}</td>
                    <td>{$t['category']}</td>
                    <td>{$t['status']}</td>
                    <td>
                        <a href='detailsTournament.php?id={$t['id']}'>View</a>
                    </td>
                  </tr>";
        }
        echo "</table>";
    } else {
        echo "<p style='color: red; text-align: center;'>No tournaments found with name: '$query'</p>";
    }
    mysqli_close($con);
}
?>