<?php

declare(strict_types=1);

namespace App\Services;

use Framework\Database;

class TransactionsService
{
  public function __construct(private Database $db) {}

  public function selectCategoriesIncomes(): Database
  {
    $categories = $this->db->query(
      "SELECT name FROM incomes_category_default"
    );
    return $categories->fetchAllResults();
  }

  public function selectCategoriesPaymentMethods(): Database
  {
    $categories = $this->db->query(
      "SELECT name FROM payment_methods_default"
    );
    return $categories->fetchAllResults();
  }
}
