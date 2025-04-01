<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
// Set the correct Content-Type for JSON responses
header('Content-Type: application/json');

// Prevent any output before sending JSON
ob_start(); // Start output buffering to prevent accidental output

require_once ('NutritionAI.php');



// Your PHP code follows
error_reporting(E_ALL);
ini_set('display_errors', 0);


// Retrieve POST values, ensuring they are set and fall back to 0 if not
$calories = isset($_POST['calories']) ? $_POST['calories'] : 0;
$carbs = isset($_POST['carbs']) ? $_POST['carbs'] : 0;
$protein = isset($_POST['protein']) ? $_POST['protein'] : 0;
$fat = isset($_POST['fat']) ? $_POST['fat'] : 0;

// Check if the values are valid (numeric values)
if (!is_numeric($calories) || !is_numeric($carbs) || !is_numeric($protein) || !is_numeric($fat)) {
    // Return an error if any of the values are not valid numbers
    echo json_encode(["error" => "Invalid input values"]);
    exit;
}


class NutritionInsights {
    private $userData;
    private $recommendedMacros = [
        'carbs' => 50, // 50% of daily intake
        'protein' => 30, // 30% of daily intake
        'fat' => 20 // 20% of daily intake
    ];

    public function __construct($userData) {
        $this->userData = $userData;
    }

	function getHealthRecommendations($calories, $carbs, $protein, $fat) {
		$recommendations = [];
		
		// Recommended macro distribution (in percentage of total calories)
		$idealCarbPct = 50;  // 45-65% of total calories
		$idealProteinPct = 20; // 10-35% of total calories
		$idealFatPct = 30;  // 20-35% of total calories
		
		// Convert grams to calories
		$caloriesFromCarbs = $carbs * 4;
		$caloriesFromProtein = $protein * 4;
		$caloriesFromFat = $fat * 9;
		
		$totalMacroCalories = $caloriesFromCarbs + $caloriesFromProtein + $caloriesFromFat;
		
		// Check if input matches total calorie intake
		if ($totalMacroCalories > $calories * 1.1 || $totalMacroCalories < $calories * 0.9) {
			$recommendations[] = "Your macro intake does not match your total calorie intake. Double-check your values.";
		}
		
		// Calculate actual macro percentages
		$carbPct = ($caloriesFromCarbs / $calories) * 100;
		$proteinPct = ($caloriesFromProtein / $calories) * 100;
		$fatPct = ($caloriesFromFat / $calories) * 100;
		
		// Provide recommendations based on intake
		if ($carbPct > $idealCarbPct + 10) {
			$recommendations[] = "Your carbohydrate intake is too high. Consider reducing refined sugars and processed foods.";
		} elseif ($carbPct < $idealCarbPct - 10) {
			$recommendations[] = "Your carbohydrate intake is too low. Include more whole grains, fruits, and vegetables.";
		}
		
		if ($proteinPct > $idealProteinPct + 10) {
			$recommendations[] = "Your protein intake is excessive. Too much protein can strain the kidneys. Stick to lean sources.";
		} elseif ($proteinPct < $idealProteinPct - 10) {
			$recommendations[] = "Your protein intake is too low. Add more lean meats, fish, beans, and dairy to your diet.";
		}
		
		if ($fatPct > $idealFatPct + 10) {
			$recommendations[] = "Your fat intake is too high. Reduce saturated fats and opt for healthy fats like olive oil and nuts.";
		} elseif ($fatPct < $idealFatPct - 10) {
			$recommendations[] = "Your fat intake is too low. Healthy fats are essential for brain function. Add avocados, nuts, and seeds.";
		}
		
		if (empty($recommendations)) {
			$recommendations[] = "Your macronutrient balance is healthy. Keep up the good work!";
		}
		
		return $recommendations;
	}
	
    public function analyzeIntake() {
        $totalCalories = $this->userData['calories'];
        $intake = $this->userData['macros'];
        
        $suggestions = [];

		$ai_suggestions = new NutritionAI($intake['protein'], $intake['carbs'], $intake['fat'], $totalCalories);
		
		$recommendations = $ai_suggestions->analyzeIntake();
		
        foreach ($this->recommendedMacros as $macro => $recommended) {
            $actual = ($intake[$macro] / $totalCalories) * 100;
            if ($actual < $recommended - 5) {
                $suggestions[] = "Increase your intake of foods rich in $macro, like " . $this->suggestFoods($macro) . ".";
            } elseif ($actual > $recommended + 5) {
                $suggestions[] = "Reduce your intake of high-$macro foods to balance your diet.";
            }
        }
        
        
        // Mockup of macros breakdown
        $macros = [
            ["name" => "Carbs", "value" => $intake['carbs']],
            ["name" => "Protein", "value" => $intake['protein']],
            ["name" => "Fat", "value" => $intake['fat']],
        ];

        // Mockup of recommendations/insights
        $insights = [
            "Increase your fiber intake for better digestion",
            "Boost protein intake for muscle recovery",
            "Reduce fat intake to manage cholesterol"
        ];

        // Send back data as JSON
        $ret = [
            "macros" => $macros,
            "suggestions" => $recommendations
        ];

        
        
        return empty($suggestions) ? ["Your diet is well-balanced!"] : $ret;
    }

    private function suggestFoods($macro) {
        // ToDo: Increase list items 
        $foodSuggestions = [
            'carbs' => ['whole grains', 'fruits', 'vegetables'],
            'protein' => ['chicken', 'fish', 'lentils'],
            'fat' => ['nuts', 'avocado', 'olive oil']
        ];
        return implode(', ', $foodSuggestions[$macro]);
    }
}

// API Endpoint to fetch insights
// header('Content-Type: application/json');
$userData = [
    'calories' => $_POST['calories'] ?? 2000,
    'macros' => [
        'carbs' => $_POST['carbs'] ?? 800,
        'protein' => $_POST['protein'] ?? 400,
        'fat' => $_POST['fat'] ?? 800
    ]
];


$insights = new NutritionInsights($userData);
echo json_encode(["insights" => $insights->analyzeIntake()], JSON_PRETTY_PRINT);

// Clean the output buffer to avoid unwanted content in the response
ob_end_flush();

?>
