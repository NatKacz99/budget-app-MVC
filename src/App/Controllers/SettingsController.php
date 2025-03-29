<?php

declare(strict_types=1);

namespace App\Controllers;

use Framework\TemplateEngine;
use App\Services\{SettingsService};

class SettingsController
{
  public function __construct(
    private TemplateEngine $view,
    private SettingsService $settingsService
  ) {}
  public function editView()
  {
    $categoriesIncomes = $this->settingsService->selectCategoriesIncomesAssignedToUsers()->results;
    $categoriesExpenses = $this->settingsService->selectCategoriesExpensesAssignedToUsers()->results;
    $categoriesPaymentMethods = $this->settingsService->selectCategoriesPaymentMethodsAssignedToUsers()->results;
    echo $this->view->render(
      "settings.php",
      [
        'categoriesIncomes' => $categoriesIncomes,
        'categoriesExpenses' => $categoriesExpenses,
        'categoriesPaymentMethods' => $categoriesPaymentMethods
      ]
    );
  }
  public function edit()
  {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['categoryIncome']) && isset($_POST['changeCategoryIncome'])) {
      $this->settingsService->editIncomeNameCategory($_POST);
    }
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['categoryExpense']) && isset($_POST['changeCategoryExpense'])) {
      $this->settingsService->editExpenseNameCategory($_POST);
    }
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['categoryPaymentMethod']) && isset($_POST['changeCategoryPaymentMethod'])) {
      $this->settingsService->editPaymentMethodName($_POST);
    }
    redirectTo('/settings');
  }
}
