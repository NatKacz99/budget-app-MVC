<?php

declare(strict_types=1);

namespace App\Services;

use Framework\Database;
use Framework\Exceptions\ValidationException;
use PDOException;
use Exception;

class UserService
{
  public function __construct(private Database $db) {}

  public function isEmailTaken(string $email)
  {
    $emailCount = $this->db->query(
      "SELECT COUNT(*) FROM users WHERE email = :email",
      [
        'email' => $email
      ]
    )->count();

    if ($emailCount > 0) {
      throw new ValidationException(['email' => ['Istnieje konto z podanym adresem email.']]);
    }
  }

  public function setMaxIdIncomesAssignedToUsers()
  {
    $_SESSION['user'] = $this->db->id();

    $row = $this->db->query("SELECT MAX(id) AS max_id FROM incomes_category_assigned_to_users")->find();

    $max_id = $row['max_id'];

    if ($max_id !== null) {
      $next_id = $max_id + 1;
      $this->db->query("ALTER TABLE incomes_category_assigned_to_users AUTO_INCREMENT = $next_id");
    }
  }

  public function attributeIncomesCategoriesToRegisteredUser(int $user_id)
  {
    try {
      $this->db->query(
        "INSERT INTO incomes_category_assigned_to_users (user_id, name)
           SELECT :user_id, name FROM incomes_category_default",
        ['user_id' => $user_id]
      );
    } catch (PDOException $e) {
      throw new Exception("Błąd podczas dodawania kategorii przychodów: " . $e->getMessage());
    }
  }

  public function attributeExpensesCategoriesToRegisteredUser(int $user_id)
  {
    try {
      $this->db->query(
        "INSERT INTO expenses_category_assigned_to_users (user_id, name)
           SELECT :user_id, name FROM expenses_category_default",
        ['user_id' => $user_id]
      );
    } catch (PDOException $e) {
      throw new Exception("Błąd podczas dodawania kategorii wydatków: " . $e->getMessage());
    }
  }

  public function attributePaymentMethodsToRegisteredUser(int $user_id)
  {
    try {
      $this->db->query(
        "INSERT INTO payment_methods_assigned_to_users (user_id, name)
           SELECT :user_id, name FROM payment_methods_default",
        ['user_id' => $user_id]
      );
    } catch (PDOException $e) {
      throw new Exception("Błąd podczas dodawania metody płatności: " . $e->getMessage());
    }
  }

  public function create(array $formData)
  {
    $password = password_hash($formData['password'], PASSWORD_BCRYPT, ['cost' => 12]);

    if (!isset($formData['name'])) {
      throw new \Exception('Pole "name" jest wymagane.');
    }
    try {
      $this->db->query(
        "INSERT INTO users(name, password, email) VALUES(:name, :password, :email)",
        [
          'name' => $formData['name'],
          'email' => $formData['email'],
          'password' => $password
        ]
      );
    } catch (\Exception $e) {
      echo "Błąd przy zapisie użytkownika: " . $e->getMessage();
      throw $e;
    }

    $user_id = $this->db->id();

    if (!$user_id) {
      throw new Exception("Błąd: Nie udało się uzyskać ID nowo zarejestrowanego użytkownika.");
    }

    $_SESSION['user'] = $user_id;

    $this->setMaxIdIncomesAssignedToUsers();
    $this->attributeIncomesCategoriesToRegisteredUser((int) $user_id);
    $this->attributeExpensesCategoriesToRegisteredUser((int) $user_id);
    $this->attributePaymentMethodsToRegisteredUser((int) $user_id);

    session_regenerate_id();
  }

  public function login(array $formData)
  {
    $user = $this->db->query(
      "SELECT * FROM USERS WHERE email = :email",
      ['email' => $formData['email']]
    )->find();

    $passwordsMatch = password_verify($formData['password'], $user['password'] ?? '');

    if (!$user || !$passwordsMatch) {
      throw new ValidationException(['password' => ['Niepoprawny email i/lub hasło.']]);
    }

    session_regenerate_id();

    $_SESSION['user'] = $user['id'];
    $_SESSION['name'] = $user['name'];
    unset($_SESSION['selected_period']);
    unset($_SESSION['startDay']);
    unset($_SESSION['endDay']);
  }

  public function logout()
  {
    unset($_SESSION['user']);
    session_destroy();

    session_regenerate_id();
  }
}
