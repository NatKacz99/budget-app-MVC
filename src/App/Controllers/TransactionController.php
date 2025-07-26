<?php

declare(strict_types=1);

namespace App\Controllers;

use Framework\TemplateEngine;
use App\Services\{ValidatorService, TransactionsService, GeminiAdvisor};

class TransactionController
{
  public function __construct(
    private TemplateEngine $view,
    private ValidatorService $validatorService,
    private TransactionsService $transactionsService,
    private GeminiAdvisor $geminiAdvisor
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
    $limitsExpenses = $this->transactionsService->getAllExpenseLimits()->results;

    echo $this->view->render("transactions/add_expense.php", [
      'categoriesPaymentMethods' => $categoriesPaymentMethods,
      'categoriesExpenses' => $categoriesExpenses,
      'limitsExpenses' => $limitsExpenses,
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

    $financialAdvice = '';
    if (!empty($categoriesIncomes) || !empty($categoriesExpenses)) {
      try {
        $financialData = [
          'balance' => $balance,
          'totalIncomes' => $total_sum_incomes,
          'totalExpenses' => $total_sum_expenses,
          'categoriesIncomes' => $categoriesIncomes,
          'categoriesExpenses' => $categoriesExpenses,
          'period' => $this->getPeriodDisplayName($selected_period)
        ];

        $financialAdvice = $this->geminiAdvisor->generateFinancialAdvice($financialData);
      } catch (\Exception $e) {
        $financialAdvice = '';
      }
    }

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
        'balance' => $balance,
        'financialAdvice' => $financialAdvice,
      ]
    );
  }

  private function getPeriodDisplayName(string $period): string
  {
    return match ($period) {
      'bieżący_miesiąc' => 'bieżący miesiąc',
      'poprzedni_miesiąc' => 'poprzedni miesiąc',
      'bieżący_rok' => 'bieżący rok',
      'niestandardowy' => 'okres niestandardowy',
      default => 'bieżący miesiąc'
    };
  }

  public function setExpenseLimit()
  {
    header('Content-Type: application/json');

    $categoryName = $_GET['category'] ?? null;

    if (!$categoryName) {
      echo json_encode(['error' => true, 'message' => 'Brak kategorii']);
      return;
    }

    $limitData = $this->transactionsService->getExpenseLimit($categoryName);

    if (!$limitData || !$limitData['expense_limit'] || $limitData == 0) {
      echo json_encode(['has_limit' => false]);
    } else {
      echo json_encode([
        'has_limit' => true,
        'limit' => $limitData['expense_limit']
      ]);
    }
  }

  public function getMonthlyExpenseSum()
  {
    header('Content-Type: application/json');

    $date = $_GET['date'] ?? date('Y-m-d');
    $category = $_GET['category'] ?? null;

    if (!$category || $category === 'Wybierz kategorię wydatku') {
      echo json_encode(['sum' => 0, 'message' => 'Wybierz kategorię']);
      return;
    }

    $sum = $this->transactionsService->getMonthExpensesForCategory($date, $category);

    echo json_encode([
      'sum' => $sum,
      'formatted' => number_format($sum, 2) . ' zł'
    ]);
  }

  public function getMonthlyLimitBalance()
  {
    header('Content-Type: application/json');

    $date = $_GET['date'] ?? date('Y-m-d');
    $category = $_GET['category'] ?? null;

    if (!$category || $category === 'Wybierz kategorię wydatku') {
      echo json_encode(['message' => 'Wybierz kategorię']);
      return;
    }

    $limitData = $this->transactionsService->getExpenseLimit($category);
    if (
      !$limitData ||
      !isset($limitData['expense_limit']) ||
      $limitData['expense_limit'] === null ||
      (float)$limitData['expense_limit'] <= 0
    ) {

      echo json_encode([
        'has_limit' => false,
        'message' => 'Brak ustawionego limitu dla tej kategorii'
      ]);
      return;
    }
    $sum = $this->transactionsService->getMonthExpensesForCategory($date, $category);

    $balance = (float)$limitData['expense_limit'] - $sum;

    echo json_encode([
      'has_limit' => true,
      'balance' => number_format($balance, 2) . ' zł',
      'balance_raw' => $balance,
      'limit' => (float)$limitData['expense_limit'],
      'used' => $sum
    ]);
  }
}
