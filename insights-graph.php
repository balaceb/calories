<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nutrition Insights</title>

    <style>
        body { font-family: Arial, sans-serif; margin: 20px; text-align: center; }
        .container { max-width: 600px; margin: auto; }
        input, button { display: block; margin: 10px auto; padding: 8px; width: 90%; }
        .card { padding: 15px; background: #f8f9fa; border-radius: 8px; margin-top: 20px; text-align: left; }
        canvas { max-width: 100%; }
    </style>
    
</head>
<body>
    <div class="container">
        <h2>Enter Your Daily Intake</h2>
        <form id="nutritionForm">
            <input type="number" name="calories" id="calories" placeholder="Calories" required>
            <input type="number" name="carbs" id="carbs" placeholder="Carbs (g)" required>
            <input type="number" name="protein" id="protein" placeholder="Protein (g)" required>
            <input type="number" name="fat" id="fat" placeholder="Fat (g)" required>
            <button type="submit">Get Insights</button>
        </form>

        <div id="output" style="display: none;">
            <div class="card">
                <h3>Recommendations</h3>
                <ul id="insightsList"></ul>
            </div>
            <div class="card">
                <h3>Macronutrient Breakdown</h3>
                <canvas id="nutritionChart"></canvas>
            </div>
        </div>
    </div>

    <script>
        document.getElementById("nutritionForm").addEventListener("submit", function(event) {
            event.preventDefault();

            let formData = new FormData(this);

            fetch("http://localhost/caloriemonitor/insights.php", {
                method: "POST",
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById("output").style.display = "block";

                // Display insights
                let insightsList = document.getElementById("insightsList");
                insightsList.innerHTML = "";
                data.insights.suggestions.forEach(insight => {
                    let li = document.createElement("li");
                    li.textContent = insight;
                    insightsList.appendChild(li);
                });

				console.log("aaaaaaaaaaaaa");
				console.log(data);
				
                // Render Pie Chart
                let ctx = document.getElementById("nutritionChart").getContext("2d");
                if (window.myChart) {
                    window.myChart.destroy();
                }
                window.myChart = new Chart(ctx, {
                    type: 'pie',
                    data: {
                        labels: data.insights.macros.map(m => m.name),
                        datasets: [{
                            data: data.insights.macros.map(m => m.value),
                            backgroundColor: ['#0088FE', '#00C49F', '#FFBB28']
                        }]
                    }
                });
            })
            .catch(error => console.error("Error:", error));
        });
    </script>
    
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
</body>
</html>
