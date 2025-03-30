<?php

declare(strict_types=1);

namespace App\Services;

use Framework\Database;

class SettingsService
{
  public function __construct(private Database $db) {}

  public function selectCategoriesIncomesAssignedToUsers(): Database
  {
    $categories = $this->db->query(
      "SELECT name FROM incomes_category_assigned_to_users
      WHERE incomes_category_assigned_to_users.user_id = :user_id",
      [
        'user_id' => $_SESSION['user']
      ]
    );
    return $categories->fetchAllResults();
  }
  public function selectCategoriesExpensesAssignedToUsers(): Database
  {
    $categories = $this->db->query(
      "SELECT name FROM expenses_category_assigned_to_users
      WHERE expenses_category_assigned_to_users.user_id = :user_id",
      [
        'user_id' => $_SESSION['user']
      ]
    );
    return $categories->fetchAllResults();
  }
  public function selectCategoriesPaymentMethodsAssignedToUsers(): Database
  {
    $categories = $this->db->query(
      "SELECT name FROM payment_methods_assigned_to_users
      WHERE payment_methods_assigned_to_users.user_id = :user_id",
      [
        'user_id' => $_SESSION['user']
      ]
    );
    return $categories->fetchAllResults();
  }
  public function editIncomeNameCategory($formData)
  {
    $this->db->query(
      "UPDATE incomes_category_assigned_to_users 
      SET name = :name 
      WHERE user_id = :user_id AND name = :old_name",
      [
        'user_id' => $_SESSION['user'],
        'name' => $formData['changeCategoryIncome'],
        'old_name' => $formData['categoryIncome']
      ]
    );
  }
  public function editExpenseNameCategory($formData)
  {
    $this->db->query(
      "UPDATE expenses_category_assigned_to_users 
      SET name = :name 
      WHERE user_id = :user_id AND name = :old_name",
      [
        'user_id' => $_SESSION['user'],
        'name' => $formData['changeCategoryExpense'],
        'old_name' => $formData['categoryExpense']
      ]
    );
  }
  public function editPaymentMethodName($formData)
  {
    $this->db->query(
      "UPDATE payment_methods_assigned_to_users 
      SET name = :name 
      WHERE user_id = :user_id AND name = :old_name",
      [
        'user_id' => $_SESSION['user'],
        'name' => $formData['changeCategoryPaymentMethod'],
        'old_name' => $formData['categoryPaymentMethod']
      ]
    );
  }
  public function addedNewCategoryIncome($formData)
  {
    $this->db->query(
      "INSERT INTO incomes_category_assigned_to_users (user_id, name)
      VALUES (:user_id, :addedCategoryIncome)",
      [
        'user_id' => $_SESSION['user'],
        'addedCategoryIncome' => $formData['addedCategoryIncome']
      ]
    );
  }
}
