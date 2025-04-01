<?php
include("../conn/conn.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['calorie_date'], $_POST['calorie_amount'])) {
        $calorieDate = $_POST['calorie_date'];
        $calorieAmount = $_POST['calorie_amount'];

        try {
            $stmt = $conn->prepare("INSERT INTO tbl_calorie (calorie_date, calorie_amount) VALUES (:calorie_date, :calorie_amount)");
            
            $stmt->bindParam(":calorie_date", $calorieDate, PDO::PARAM_STR); 
            $stmt->bindParam(":calorie_amount", $calorieAmount, PDO::PARAM_STR);

            $stmt->execute();

            header("Location: http://localhost/caloriemonitor/");

            exit();
        } catch (PDOException $e) {
            echo "Error:" . $e->getMessage();
        }

    } else {
        echo "
            <script>
                alert('Please fill in all fields!');
                window.location.href = 'http://localhost/caloriemonitor/';
            </script>
        ";
    }
}
?>
