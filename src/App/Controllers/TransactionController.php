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
    redirectTo('/balance');
  }

  public function createViewShowBalance()
  {
    $page = $_GET['pageNum'] ?? 1;
    $page = max(1, (int) $page);
    $length = 3;
    $offset = ($page - 1) * $length;
    $searchTerm = $_GET['searchTerm'] ??  null;

    $userId = $_SESSION['user'];
    if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['time-slot'])) {
      $_SESSION['selected_period'] = $_GET['time-slot'];

      if ($_GET['time-slot'] === 'niestandardowy' && !empty($_GET['startDay']) && !empty($_GET['endDay'])) {
        $_SESSION['startDay'] = $_GET['startDay'];
        $_SESSION['endDay'] = $_GET['endDay'];
      }
      header("Location: balance?p=1");
      exit;
    }
    $selected_period = $_SESSION['selected_period'] ?? '';

    $startDay = $endDay = null;

    switch ($selected_period) {
      case 'bieżący_miesiąc':
        $startDay = date('Y-m-01');
        $endDay = date('Y-m-d');
        break;
      case 'poprzedni_miesiąc':
        $startDay = date('Y-m-01', strtotime('-1 month'));
        $endDay = date('Y-m-t', strtotime('-1 month'));
        break;
      case 'bieżący_rok':
        $startDay = date('Y-01-01');
        $endDay = date('Y-m-d');
        break;
      case 'niestandardowy':
        $startDay = $_SESSION['startDay'] ?? '';
        $endDay = $_SESSION['endDay'] ?? '';
        break;
    }

    if (!empty($startDay) && !empty($endDay)) {
      $sumIncomes = $this->transactionsService->sumIncomesByPeriod($startDay, $endDay, $userId);
      $sumExpenses = $this->transactionsService->sumExpensesByPeriod($startDay, $endDay, $userId);
      $categoriesIncomes = $this->transactionsService->getCategoriesUserIncomesByPeriod($startDay, $endDay);
      $categoriesExpenses = $this->transactionsService->getCategoriesUserExpensesByPeriod($startDay, $endDay);

      [$incomes, $incomesCount] = $this->transactionsService->getUserIncomesByPeriod(
        $startDay,
        $endDay,
        $length,
        $offset
      );
      [$expenses, $expensesCount] = $this->transactionsService->getUserExpensesByPeriod(
        $startDay,
        $endDay,
        $length,
        $offset
      );
    } else {
      $sumIncomes = $this->transactionsService->sumIncomes($userId);
      $sumExpenses = $this->transactionsService->sumExpenses($userId);
      $categoriesIncomes = $this->transactionsService->getCategoriesUserIncomes();
      $categoriesExpenses = $this->transactionsService->getCategoriesUserExpenses();

      [$incomes, $incomesCount] = $this->transactionsService->getUserIncomes(
        $length,
        $offset
      );
      [$expenses, $expensesCount] = $this->transactionsService->getUserExpenses(
        $length,
        $offset
      );
    }

    $dataPointsIncomes = [];
    $total_sum_incomes = 0;

    foreach ($categoriesIncomes as $row) {
      $total_sum_incomes += $row['sumCategory'];
    }

    foreach ($categoriesIncomes as $row) {
      if ($total_sum_incomes > 0) {
        $dataPointsIncomes[] = array(
          "label" => $row['name'],
          "y" => ($row['sumCategory'] / $total_sum_incomes) * 100
        );
      }
    }

    $dataPointsExpenses = [];
    $total_sum_expenses = 0;

    foreach ($categoriesExpenses as $row) {
      $total_sum_expenses += $row['sumCategory'];
    }

    foreach ($categoriesExpenses as $row) {
      if ($total_sum_expenses > 0) {
        $dataPointsExpenses[] = array(
          "label" => $row['name'],
          "y" => ($row['sumCategory'] / $total_sum_expenses) * 100
        );
      }
    }

    $balance = $total_sum_incomes - $total_sum_expenses;
    $balance_sheet = $balance . " zł";

    $totalCount = max($incomesCount, $expensesCount);

    $lastPage = (int) max(1, ceil($totalCount / $length));
    $page = min($page, $lastPage);
    $pages = $lastPage ? range(1, $lastPage) : [];

    $pageLinks = array_map(
      fn($pageNum) => http_build_query([
        'pageNum' => $pageNum,
        'searchTerm' => $searchTerm
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
          'pageNum' => max(1, $page - 1),
          'searchTerm' =>  $searchTerm
        ]),
        'lastPage' => $lastPage,
        'nextPageQuery' => http_build_query([
          'pageNum' => min($lastPage, $page + 1),
          'searchTerm' => $searchTerm
        ]),
        'pageLinks' => $pageLinks,
        'searchTerm' => $searchTerm,
        'sumIncomes' => $sumIncomes,
        'sumExpenses' => $sumExpenses,
        'expensesCount' => $expensesCount,
        'incomesCount' => $incomesCount,
        'selected_period' => $selected_period,
        'startDay' => $startDay,
        'endDay' => $endDay,
        'dataPointsIncomes' => $dataPointsIncomes,
        'dataPointsExpenses' => $dataPointsExpenses,
        'categoriesIncomes' => $categoriesIncomes,
        'categoriesExpenses' => $categoriesExpenses,
        'balance_sheet' => $balance_sheet,
        'balance' => $balance
      ]
    );
  }

  public function setExpenseLimit($categoryName)
  {
    echo json_encode($limitData = $this->transactionsService->getExpenseLimit($categoryName), JSON_UNESCAPED_UNICODE);

    if (!$limitData || !isset($limitData['limit']) || $limitData['limit'] == 0) {
      return [
        'has_limit' => false,
        'message' => 'Brak ustawionego limitu dla tej kategorii'
      ];
    }
  }
}
