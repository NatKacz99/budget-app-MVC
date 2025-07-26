<?php

declare(strict_types=1);

namespace App\Services;

use GeminiAPI\Client;
use GeminiAPI\Resources\ModelName;
use GeminiAPI\Resources\Parts\TextPart;

class GeminiAdvisor
{
  private Client $client;

  public function __construct()
  {
    $apiKey = $_ENV['GEMINI_API_KEY'];

    if (empty($apiKey)) {
      error_log("Błąd: nieprawidłowa konfiguracja lub brak klucza API Gemini");
    }

    $this->client = new Client($apiKey);
  }

  public function generateFinancialAdvice(array $financialData): string
  {
    error_log("GeminiAdvisor: Otrzymane dane finansowe: " . json_encode($financialData));

    $balance = (float)($financialData['balance'] ?? 0);
    $totalIncomes = (float)($financialData['totalIncomes'] ?? 0);
    $totalExpenses = (float)($financialData['totalExpenses'] ?? 0);
    $categoriesExpenses = $financialData['categoriesExpenses'] ?? [];
    $categoriesIncomes = $financialData['categoriesIncomes'] ?? [];
    $period = $financialData['period'] ?? 'bieżący miesiąc';

    if (empty($categoriesExpenses) && empty($categoriesIncomes) && $totalIncomes == 0 && $totalExpenses == 0) {
      return "Brak danych finansowych do analizy. Dodaj kilka transakcji, aby otrzymać spersonalizowaną poradę.";
    }
    $categoriesExpenses = array_map(function ($category) {
      if (isset($category['sumCategory'])) {
        $category['sumCategory'] = (float)$category['sumCategory'];
      }
      return $category;
    }, $categoriesExpenses);

    $categoriesIncomes = array_map(function ($category) {
      if (isset($category['sumCategory'])) {
        $category['sumCategory'] = (float)$category['sumCategory'];
      }
      return $category;
    }, $categoriesIncomes);

    $prompt = $this->buildFinancialPrompt($balance, $totalIncomes, $totalExpenses, $categoriesExpenses, $categoriesIncomes, $period);

    try {
      $response = $this->client->generativeModel(ModelName::GEMINI_1_5_FLASH)->generateContent(
        new TextPart($prompt)
      );

      $result = $response->text();

      return $result;
    } catch (\Exception $e) {
      // Debug: zaloguj szczegóły błędu
      error_log("GeminiAdvisor: BŁĄD API - " . $e->getMessage());

      return "Przepraszam, nie mogę obecnie wygenerować porady finansowej. Błąd: " . $e->getMessage();
    }
  }

  private function buildFinancialPrompt(
    float $balance,
    float $totalIncomes,
    float $totalExpenses,
    array $categoriesExpenses,
    array $categoriesIncomes,
    string $period
  ): string {
    $prompt = "Jesteś doradcą finansowym. Przeanalizuj następujące dane finansowe użytkownika za okres: {$period}\n\n";

    $prompt .= "BILANS: " . number_format((float)$balance, 2) . " zł\n";
    $prompt .= "CAŁKOWITE PRZYCHODY: " . number_format((float)$totalIncomes, 2) . " zł\n";
    $prompt .= "CAŁKOWITE WYDATKI: " . number_format((float)$totalExpenses, 2) . " zł\n\n";

    if (!empty($categoriesIncomes)) {
      $prompt .= "KATEGORIE PRZYCHODÓW:\n";
      foreach ($categoriesIncomes as $category) {
        $prompt .= "- {$category['name']}: " . number_format((float)$category['sumCategory'], 2) . " zł\n";
      }
      $prompt .= "\n";
    }

    if (!empty($categoriesExpenses)) {
      $prompt .= "KATEGORIE WYDATKÓW:\n";
      foreach ($categoriesExpenses as $category) {
        $prompt .= "- {$category['name']}: " . number_format((float)$category['sumCategory'], 2) . " zł\n";
      }
      $prompt .= "\n";
    }

    $prompt .= "Na podstawie tych danych, udziel konkretnych, praktycznych porad finansowych w języku polskim. ";
    $prompt .= "Rada powinna być zwięzła (maksymalnie 3-4 zdania) i zawierać konkretne sugestie dotyczące:\n";
    $prompt .= "1. Optymalizacji wydatków w kategoriach gdzie wydaje się najwięcej\n";
    $prompt .= "2. Możliwości zwiększenia oszczędności\n";
    $prompt .= "3. Ogólnej strategii zarządzania budżetem\n\n";
    $prompt .= "Odpowiedź powinna być przyjazna i motywująca.";

    return $prompt;
  }
}
