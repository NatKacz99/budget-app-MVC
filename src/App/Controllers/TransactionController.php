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

  public function createShowBalance()
  {
    $this->validatorService->validateBalance($_POST);
    redirectTo('/balance');
  }

  public function createViewShowBalance()
  {
    $page = $_GET['p'] ?? 1;
    $page = max(1, (int) $page);
    $length = 3;
    $offset = ($page - 1) * $length;
    $searchTerm = $_GET['s'] ??  null;

    $userId = $_SESSION['user'];
    $sumIncomes = $this->transactionsService->sumIncomes($userId);
    $sumExpenses = $this->transactionsService->sumExpenses($userId);

    [$incomes, $incomesCount] = $this->transactionsService->getUserIncomes(
      $length,
      $offset
    );
    [$expenses, $expensesCount] = $this->transactionsService->getUserExpenses(
      $length,
      $offset
    );

    $totalCount = max($incomesCount, $expensesCount);

    $lastPage = (int) max(1, ceil($totalCount / $length));
    $page = min($page, $lastPage);
    $pages = $lastPage ? range(1, $lastPage) : [];

    $pageLinks = array_map(
      fn($pageNum) => http_build_query([
        'p' => $pageNum,
        's' => $searchTerm
      ]),
      $pages
    );

    echo $this->view->render(
      "transactions/show_balance.php",
      [
        'incomes' => $incomes,
        'expenses' => $expenses,
        'currentPage' => $page,
        'previousPageQuery' => http_build_query([
          'p' => max(1, $page - 1),
          's' =>  $searchTerm
        ]),
        'lastPage' => $lastPage,
        'nextPageQuery' => http_build_query([
          'p' => min($lastPage, $page + 1),
          's' => $searchTerm
        ]),
        'pageLinks' => $pageLinks,
        'searchTerm' => $searchTerm,
        'sumIncomes' => $sumIncomes,
        'sumExpenses' => $sumExpenses,
        'expensesCount' => $expensesCount,
        'incomesCount' => $incomesCount
      ]
    );
  }
}
