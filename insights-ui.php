<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nutrition Insights</title>
    <link rel="stylesheet" href="style.css">
    <script>
        async function getInsights() {
            let formData = new FormData(document.getElementById("nutritionForm"));
            let response = await fetch("insights.php", {
                method: "POST",
                body: formData
            });
            let result = await response.json();
            document.getElementById("insights").innerHTML = result.insights.map(insight => `<p>${insight}</p>`).join("");
        }
    </script>
</head>
<body>
    <div class="container">
        <h2>Enter Your Daily Intake</h2>
        <form id="nutritionForm" onsubmit="event.preventDefault(); getInsights();">
            <input type="number" name="calories" placeholder="Calories" required><br>
            <input type="number" name="carbs" placeholder="Carbs (g)" required><br>
            <input type="number" name="protein" placeholder="Protein (g)" required><br>
            <input type="number" name="fat" placeholder="Fat (g)" required><br>
            <button type="submit">Get Insights</button>
        </form>
        <div id="insights"></div>
    </div>
</body>
</html>
