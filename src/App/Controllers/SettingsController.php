<?php

declare(strict_types=1);

namespace App\Controllers;

use Framework\TemplateEngine;
use App\Services\{ValidatorService, SettingsService, TransactionsService};

class SettingsController
{
  public function __construct(
    private TemplateEngine $view,
    private ValidatorService $validatorService,
    private SettingsService $settingsService,
    private TransactionsService $transactionsService
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
      if ((!empty($_POST['name']))) {
        $this->validatorService->validateEdittingUserName($_POST);
        $this->settingsService->updateUserName($_POST);
      }
      if (!empty($_POST['email'])) {
        $this->validatorService->validateEdittingUserEmail($_POST);
        $this->settingsService->updateUserEmail($_POST);
      }
      if (!empty($_POST['confirmPassword'])) {
        $this->validatorService->validatePasswordEditting($_POST);
        $this->settingsService->updateUserPassword($_POST);
      }
      if (empty($_POST['password']) && !empty($_POST['confirmPassword'])) {
        $_SESSION['password_error'] = "Pole dla nowego hasła nie może byc puste.";
      }

      if (isset($_POST['categoryExpense']) && !empty($_POST['categoryExpense'])) {
        $selectedCategory = $_POST['categoryExpense'];
        $limitFieldName = 'limit_' . $selectedCategory;

        if (isset($_POST[$limitFieldName]) && !empty($_POST[$limitFieldName])) {
          $limitAmount = (float) $_POST[$limitFieldName];

          if ($limitAmount > 0) {
            $this->transactionsService->updateExpenseLimit($selectedCategory, $limitAmount);
            $_SESSION['success_message'] = "Limit {$limitAmount} zł został ustawiony dla kategorii '{$selectedCategory}'";
          } else {
            $_SESSION['error_message'] = "Limit musi być większy od 0";
          }
        } else {
          $_SESSION['error_message'] = "Nie wprowadzono kwoty limitu dla wybranej kategorii";
        }
      }
    }
    redirectTo('/settings');
  }
  public function delete()
  {
    if ($_SERVER['REQUEST_METHOD']) {
      if (isset($_POST['categoryIncomeDeleted'])) {
        $this->settingsService->deleteIncome($_POST);
        redirectTo('/settings');
      }
      if (isset($_POST['categoryExpenseDeleted'])) {
        $this->settingsService->deleteExpense($_POST);
        redirectTo('/settings');
      }
      if (isset($_POST['categoryPaymentMethodDeleted'])) {
        $this->settingsService->deletePaymentmethod($_POST);
        redirectTo('/settings');
      }
      if (isset($_POST['deleteAccount'])) {
        $this->settingsService->deleteUserAccount();
        session_unset();
        session_destroy();
        redirectTo('/');
      }
    }
  }
}
