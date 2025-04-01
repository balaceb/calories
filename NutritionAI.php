<?php

require 'vendor/autoload.php'; // Load dependencies (Guzzle and Symfony DomCrawler)

use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;

class NutritionAI {
    private $protein;
    private $carbs;
    private $fat;
    private $calories;
    private $client;

    public function __construct($protein, $carbs, $fat, $calories) {
        $this->protein = $protein;
        $this->carbs = $carbs;
        $this->fat = $fat;
        $this->calories = $calories;
        $this->client = new Client();
    }

    public function fetchNutritionAdvice() {
        try {
            $response = $this->client->request('GET', 'https://www.hsph.harvard.edu/nutritionsource/healthy-eating-plate/');
            $html = $response->getBody()->getContents();
            var_dump($html);
            $crawler = new Crawler($html);
            
            $advice = $crawler->filter('article p')->each(function (Crawler $node) {
                return trim($node->text());
            });
            
            return array_slice($advice, 0, 5); // Return first 5 advice snippets
        } catch (Exception $e) {
            return ['Could not fetch live nutrition advice. Please consult a trusted source.'];
        }
    }

    public function analyzeIntake() {
        $recommendations = [];

        if ($this->carbs > 300) {
            $recommendations[] = "Your carbohydrate intake is high. Consider reducing sugar and opting for whole grains.";
        } elseif ($this->carbs < 130) {
            $recommendations[] = "Your carbohydrate intake is low. Include more fruits, vegetables, and whole grains.";
        }

        if ($this->protein < 50) {
            $recommendations[] = "Your protein intake is low. Add more lean meats, beans, and nuts to your diet.";
        } elseif ($this->protein > 150) {
            $recommendations[] = "Your protein intake is excessive. Consider balancing it with plant-based proteins.";
        }

        if ($this->fat > 80) {
            $recommendations[] = "Your fat intake is high. Reduce saturated fats and include more healthy fats from fish and nuts.";
        } elseif ($this->fat < 40) {
            $recommendations[] = "Your fat intake is low. Consider healthy fats like avocado and olive oil.";
        }

        if ($this->calories > 2500) {
            $recommendations[] = "Your calorie intake is high. Consider portion control and incorporating more fiber-rich foods.";
        } elseif ($this->calories < 1500) {
            $recommendations[] = "Your calorie intake is low. Ensure balanced meals with adequate proteins, carbs, and healthy fats.";
        }

        $liveAdvice = []; // For now we disable this  $this->fetchNutritionAdvice();
        return array_merge($recommendations, $liveAdvice);
    }
}

?>
