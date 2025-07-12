<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Services\{TransactionsService, SettingsService};

class APIController
{
  public function __construct(
    private TransactionsService $transactionsService,
    private SettingsService $settingsService
  ) {}

  public function getExpenseLimit()
  {
    header('Content-Type: application/json');

    $category = $_GET['category'] ?? null;

    if (!$category) {
      http_response_code(400);
      echo json_encode([
        'error' => true,
        'message' => 'Brak parametru category'
      ]);
      return;
    }

    $limitData = $this->transactionsService->getExpenseLimit($category);

    if (!$limitData || !$limitData['expense_limit'] || $limitData['expense_limit'] == 0) {
      echo json_encode([
        'error' => false,
        'has_limit' => false,
        'category' => $category,
        'message' => 'Brak ustawionego limitu dla tej kategorii'
      ]);
      return;
    }

    echo json_encode([
      'error' => false,
      'has_limit' => true,
      'category' => $category,
      'limit' => $limitData['expense_limit'],
      'message' => 'Limit wynosi: ' . $limitData['expense_limit'] . ' pln'
    ]);
  }
}
