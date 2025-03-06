<?php

declare(strict_types=1);

namespace App\Services;

use Framework\Database;
use Framework\Exceptions\ValidationException;

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
          'password' => $formData['password'],
        ]
      );
    } catch (\Exception $e) {
      throw $e;
    }
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
  }
}
