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
  // Poprawka: Rozbij na mniejsze metody
  public function edit()
  {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
      redirectTo('/settings');
      return;
    }

    $this->handleCategoryUpdates();
    $this->handleNewCategories();
    $this->handleUserDataUpdates();
    $this->handleLimitUpdates();

    redirectTo('/settings');
  }

  private function handleCategoryUpdates(): void
  {
    if (isset($_POST['categoryIncome'], $_POST['changeCategoryIncome'])) {
      $this->settingsService->editIncomeNameCategory($_POST);
    }

    if (isset($_POST['categoryExpense'], $_POST['changeCategoryExpense'])) {
      $this->settingsService->editExpenseNameCategory($_POST);
    }

    if (isset($_POST['categoryPaymentMethod'], $_POST['changeCategoryPaymentMethod'])) {
      $this->settingsService->editPaymentMethodName($_POST);
    }
  }

  private function handleNewCategories(): void
  {
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

  private function handleUserDataUpdates(): void
  {
    if (!empty($_POST['name'])) {
      $this->validatorService->validateEdittingUserName($_POST);
      $this->settingsService->updateUserName($_POST);
    }

    if (!empty($_POST['email'])) {
      $this->validatorService->validateEdittingUserEmail($_POST);
      $this->settingsService->updateUserEmail($_POST);
    }

    if (!empty($_POST['confirmPassword'])) {
      if (empty($_POST['password'])) {
        $_SESSION['password_error'] = "Pole dla nowego hasła nie może byc puste.";
        return;
      }
      $this->validatorService->validatePasswordEditting($_POST);
      $this->settingsService->updateUserPassword($_POST);
    }
  }

  private function handleLimitUpdates(): void
  {
    if (!isset($_POST['categoryExpense']) || empty($_POST['categoryExpense'])) {
      return;
    }

    $selectedCategory = $_POST['categoryExpense'];
    $limitFieldName = 'limit_' . $selectedCategory;

    if (!isset($_POST[$limitFieldName]) || empty($_POST[$limitFieldName])) {
      $_SESSION['error_message'] = "Nie wprowadzono kwoty limitu dla wybranej kategorii";
      return;
    }

    $limitAmount = (float) $_POST[$limitFieldName];

    if ($limitAmount <= 0) {
      $_SESSION['error_message'] = "Limit musi być większy od 0";
      return;
    }

    $this->transactionsService->updateExpenseLimit($selectedCategory, $limitAmount);
    $_SESSION['success_message'] = "Limit {$limitAmount} zł został ustawiony dla kategorii '{$selectedCategory}'";
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
