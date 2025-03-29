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
  public function editIncomeNameCategory($formData)
  {
    $this->db->query(
      "UPDATE incomes_category_assigned_to_users 
      SET name = :name 
      WHERE user_id = :user_id AND name = :old_name",
      [
        'user_id' => $_SESSION['user'],
        'name' => $formData['changeCategory'],
        'old_name' => $formData['category']
      ]
    );
  }
}
