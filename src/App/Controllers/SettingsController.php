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
    if ($_SERVER['REQUEST_METHOD']) {
      if (isset($_POST['categoryIncome']) && isset($_POST['changeCategoryIncome'])) {
        $this->settingsService->editIncomeNameCategory($_POST);
      }
      if (isset($_POST['categoryExpense']) && isset($_POST['changeCategoryExpense'])) {
        $this->settingsService->editExpenseNameCategory($_POST);
      }
      if (isset($_POST['categoryPaymentMethod']) && isset($_POST['changeCategoryPaymentMethod'])) {
        $this->settingsService->editPaymentMethodName($_POST);
      }
      if (isset($_POST['addedCategoryIncome'])) {
        $this->settingsService->addedNewCategoryIncome($_POST);
      }
      if (isset($_POST['addedCategoryExpense'])) {
        $this->settingsService->addedNewCategoryExpense($_POST);
      }
      if (isset($_POST['addedPaymentMethod'])) {
        $this->settingsService->addedNewCategoryPaymentMethod($_POST);
      }
    }
    redirectTo('/settings');
  }
  public function delete()
  {
    if ($_SERVER['REQUEST_METHOD']) {
      if (isset($_POST['categoryIncomeDeleted'])) {
        $this->settingsService->deleteIncome($_POST);
      }
      if (isset($_POST['categoryExpenseDeleted'])) {
        $this->settingsService->deleteExpense($_POST);
      }
      if (isset($_POST['categoryPaymentMethodDeleted'])) {
        $this->settingsService->deletePaymentmethod($_POST);
      }
    }
    redirectTo('/settings');
  }
}
