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
      if ((!empty($_POST['name'])) && ctype_alnum($_POST['name'])) {
        $this->settingsService->updateUserName($_POST);
      }
      if ((!empty($_POST['name'])) && !ctype_alnum($_POST['name'])) {
        $_SESSION['name_error_message'] = "Nazwa może składac się tylko z cyfr i liter (bez polskich znaków).";
      }
      if (!empty($_POST['email']) && filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $this->settingsService->updateUserEmail($_POST);
      }
      if (!empty($_POST['email']) && !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        $_SESSION['email_error_message'] = "Niepoprawny e-mail.";
      }
      if (!empty($_POST['password']) && !empty($_POST['confirmPassword']) && $_POST['password'] === $_POST['confirmPassword']) {
        $this->settingsService->updateUserPassword($_POST);
      }
      if (!empty($_POST['password']) && empty($_POST['confirmPassword'])) {
        $_SESSION['confirm_password_error'] = "Pole z potwierdzeniem hasła nie może być puste.";
      }
      if (empty($_POST['password']) && !empty($_POST['confirmPassword'])) {
        $_SESSION['password_error'] = "Pole dla nowego hasła nie może byc puste.";
      }
      if (!(($_POST['password']) === $_POST['confirmPassword'])) {
        $_SESSION['password_match_error'] = "Hasła nie pasują do siebie. Spróbuj wpisać ponownie.";
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
