<?php
include ('../conn/conn.php');

if (isset($_GET['calorie'])) {
    $calorie = $_GET['calorie'];

    try {

        $query = "DELETE FROM tbl_calorie WHERE tbl_calorie_id = '$calorie'";

        $stmt = $conn->prepare($query);

        $query_execute = $stmt->execute();

        if ($query_execute) {
            header("Location: http://localhost/caloriemonitor/");
        } else {
            header("Location: http://localhost/caloriemonitor/");
        }

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

?>