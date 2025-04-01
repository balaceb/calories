<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daily Calories Monitoring Tool</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@500&display=swap');

        * {
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background-image: linear-gradient(to right, #4facfe 0%, #00f2fe 100%);
            background-repeat: no-repeat;
            background-attachment: fixed;
        }

        .main {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .calories-container {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 40px;
            background-color: rgb(255, 255, 255);
            border-radius: 10px;
            height: 90vh;
            width: 90vw;
            box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 15px;
        }
        
        .header {
            display: flex;
            width: 100%;
            justify-content: space-between;
            border-bottom: 1px solid rgb(200, 200, 200);
            padding-bottom: 10px;
        }

        .table-graph-container {
            display: flex;
            width: 100%;
            height: 100%;
            padding: 20px
        }

        .table-container {
            width: 500px;
            padding-right: 10px;
            border-right: 1px solid rgb(200, 200, 200);
            height: 100%;
        }

        .graph-container > canvas {
            margin-left: 10px;
            width: 800px;
            height: 100% !important;
        }

        .btn-primary {
            border: none !important;
            outline: none !important;
        }
    </style>
</head>
<body>
    <div class="main">
        <div class="calories-container">
            <div class="header">
                <h3>Daily Calories Monitoring Tool</h3>
                <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#addcalorieModal">+ Add Calorie Intake</button>

                <!-- Modal -->
                <div class="modal fade" id="addcalorieModal" tabindex="-1" aria-labelledby="addcalorie" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="addcalorie">Add Calorie Intake</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form action="./endpoint/add-calorie.php" method="POST">
                                    <div class="form-group">
                                        <label for="calorieDate">Calorie Date:</label>
                                        <input type="date" class="form-control" id="calorieDate" name="calorie_date">
                                    </div>
                                    <div class="form-group">
                                        <label for="calorieAmount">Calorie Amount (in grams):</label>
                                        <input type="number" class="form-control" id="calorieAmount" name="calorie_amount">
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                        <button type="submit" class="btn btn-primary">Add</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-graph-container">
                <div class="table-container">
                    <table class="table text-center table-sm">
                        <thead>
                            <tr>
                                <th scope="col">Date</th>
                                <th scope="col">Calories (g)</th>
                                <th scope="col">Action</th>
                            </tr>
                        </thead>
                        <tbody id="calorieTableBody">
                            <?php
                                include('./conn/conn.php');

                                $stmt = $conn->prepare("SELECT * FROM tbl_calorie ORDER BY calorie_date");
                                $stmt->execute();
                                $result = $stmt->fetchAll();

                                foreach ($result as $row) {
                                    $calorieId = $row['tbl_calorie_id'];
                                    $calorieDate = $row['calorie_date'];
                                    $calorieAmount = $row['calorie_amount'];


                                    // Output the table row
                                    echo '<tr class="calorieList">';
                                    echo '<th hidden>' . $calorieId . '</th>';
                                    echo '<td>' . $calorieDate . '</td>';
                                    echo '<td>' . $calorieAmount . '</td>';
                                    echo '<td><button type="button" class="btn btn-sm btn-danger" onclick="removecalorie(' . $calorieId . ')">X</button></td>';
                                    echo '</tr>';
                                }
                            ?>
                        </tbody>
                    </table>
                </div>
                <div class="graph-container">
                    <canvas id="myChart"></canvas>
                </div>
            </div>
        </div>
    </div>   
        
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>

    <?php
        include('./conn/conn.php');

        $stmt = $conn->prepare("SELECT * FROM tbl_calorie ORDER BY calorie_date");
        $stmt->execute();
        $result = $stmt->fetchAll();

        $labels = [];
        $calories = [];

        foreach ($result as $row) {
            $calorieDate = $row['calorie_date'];
            $calorieAmount = $row['calorie_amount'];

            // Store data for chart
            $labels[] = $calorieDate;
            $calories[] = $calorieAmount;
        }
    ?>

    
    <script>
        function removecalorie(id) {
            if (confirm("Do you want to delete this calorie?")) {
                window.location = "./endpoint/delete-calorie.php?calorie=" + id;
            }
        }

        const ctx = document.getElementById('myChart');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: <?php echo json_encode($labels); ?>,
                datasets: [{
                    label: 'Total Amount of Calories per Day',
                    data: <?php echo json_encode($calories); ?>,
                    borderColor: '#4facfe',
                    backgroundColor: '#4facfe',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>
</html>