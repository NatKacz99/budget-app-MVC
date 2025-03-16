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

  public function selectCategoriesExpenses(): Database
  {
    $categories = $this->db->query(
      "SELECT name FROM expenses_category_default"
    );
    return $categories->fetchAllResults();
  }

  public function createIncome($formData)
  {
    $formattedDate = "{$formData['date']} 00:00:00";
    $income_id = $this->db->query(
      "SELECT id FROM incomes_category_assigned_to_users WHERE user_id = :user_id AND name = :category",
      [
        'user_id' => $_SESSION['user'],
        'category' => $formData['category']
      ]
    )->count();

    if (!$income_id) {
      die("Błąd: Nie znaleziono ID kategorii dla użytkownika {$_SESSION['user']} i kategorii {$formData['category']}");
    }
    $sql = "INSERT INTO incomes(user_id, income_category_assigned_to_user_id, amount, date_of_income, income_comment)
        VALUES(:user_id, :income_id, :amount, :date_of_income, :income_comment)";

    $params = [
      'user_id' => $_SESSION['user'],
      'income_id' => $income_id,
      'amount' => (float) str_replace(',', '.', $formData['price']),
      'date_of_income' => $formattedDate,
      'income_comment' => $formData['comment']
    ];
    $this->db->query($sql, $params);
  }
}
