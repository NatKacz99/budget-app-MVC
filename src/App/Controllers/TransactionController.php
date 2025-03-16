<?php

declare(strict_types=1);

namespace App\Controllers;

use Framework\TemplateEngine;
use App\Services\{ValidatorService, TransactionsService};

class TransactionController
{
  public function __construct(
    private TemplateEngine $view,
    private ValidatorService $validatorService,
    private TransactionsService $transactionsService
  ) {}

  public function createViewAddIncome()
  {
    $categories = $this->transactionsService->selectCategoriesIncomes()->results;

    echo $this->view->render("transactions/add_income.php", [
      'categories' => $categories
    ]);
  }

  public function createAddIncome()
  {
    $this->validatorService->validateIncome($_POST);
    $this->transactionsService->createIncome($_POST);
    redirectTo('/incomes');
  }

  public function createViewAddExpense()
  {
    $categoriesPaymentMethods = $this->transactionsService->selectCategoriesPaymentMethods()->results;
    $categoriesExpenses = $this->transactionsService->selectCategoriesExpenses()->results;
    echo $this->view->render("transactions/add_expense.php", [
      'categoriesPaymentMethods' => $categoriesPaymentMethods,
      'categoriesExpenses' => $categoriesExpenses
    ]);
  }

  public function createAddExpense()
  {
    $this->validatorService->validateExpense($_POST);
    $this->transactionsService->createExpense($_POST);
    redirectTo('/expenses');
  }

  public function createViewShowBalance()
  {
    $page = $_GET['p'] ?? 1;
    $page = (int) $page;
    $length = 3;
    $offset = ($page - 1) * $length;
    $searchTerm = $_GET['s'] ??  null;
    $incomes = $this->transactionsService->getUserIncomes(
      $length,
      $offset
    );
    $expenses = $this->transactionsService->getUserExpenses(
      $length,
      $offset
    );

    echo $this->view->render(
      "transactions/show_balance.php",
      [
        'incomes' => $incomes,
        'expenses' => $expenses,
        'currentPage' => $page,
        'previousPageQuery' => http_build_query([
          'p' => $page - 1,
          's' =>  $searchTerm
        ])
      ]
    );
  }

  public function createShowBalance()
  {
    $this->validatorService->validateBalance($_POST);
    redirectTo('/balance');
  }
}
