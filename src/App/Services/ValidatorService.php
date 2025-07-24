<?php

declare(strict_types=1);

namespace App\Services;

use Framework\Validator;
use Framework\Rules\{
  NameRule,
  RequiredRule,
  EmailRule,
  MatchRule,
  PriceRule,
  MenuRule,
  LengthMaxRule
};

class ValidatorService
{
  private Validator $validator;

  public function __construct()
  {
    $this->validator = new Validator();

    $this->validator->add('name', new NameRule());
    $this->validator->add('required', new RequiredRule());
    $this->validator->add('email', new EmailRule());
    $this->validator->add('match', new MatchRule());
    $this->validator->add('price', new PriceRule());
    $this->validator->add('category', new MenuRule());
    $this->validator->add('lengthMax', new LengthMaxRule());
  }

  public function validateRegister(array $formData)
  {
    $this->validator->validate($formData, [
      'name' => ['required', 'name'],
      'email' => ['required', 'email'],
      'password' => ['required'],
      'confirmPassword' => ['required', 'match:password'],
    ]);
  }

  public function validateLogin(array $formData)
  {
    $this->validator->validate($formData, [
      'email' => ['required', 'email'],
      'password' => ['required']
    ]);
  }

  public function validateIncome(array $formData)
  {
    $this->validator->validate($formData, [
      'price' => ['required', 'price'],
      'category' => ['required', 'category'],
      'comment' => ['lengthMax:100']
    ]);
  }

  public function validateExpense(array $formData)
  {
    $this->validator->validate($formData, [
      'price' => ['required', 'price'],
      'paymentMethod' => ['required', 'category'],
      'category' => ['required', 'category'],
      'comment' => ['lengthMax:100']
    ]);
  }

  public function validateEdittingUserName(array $formData)
  {
    $this->validator->validate($formData, [
      'name' => ['name'],
    ]);
  }

  public function validateEdittingUserEmail(array $formData)
  {
    $this->validator->validate($formData, [
      'email' => ['email']
    ]);
  }

  public function validatePasswordEditting(array $formData)
  {
    $this->validator->validate($formData, [
      'confirmPassword' => ['match:password']
    ]);
  }

  public function validateLimit($value, int $defaultValue = 10, int $maxValue = 1000): int
  {
    if ($value === null || $value === '') {
      return $defaultValue;
    }

    $intValue = (int) $value;

    if ($intValue < 1) {
      throw new \InvalidArgumentException("Limit must be at least 1, got: {$intValue}");
    }

    if ($intValue > $maxValue) {
      throw new \InvalidArgumentException("Limit cannot exceed {$maxValue}, got: {$intValue}");
    }

    return $intValue;
  }

  public function validateOffset($value, int $defaultValue = 0): int
  {
    if ($value === null || $value === '') {
      return $defaultValue;
    }

    $intValue = (int) $value;

    if ($intValue < 0) {
      throw new \InvalidArgumentException("Offset cannot be negative, got: {$intValue}");
    }

    return $intValue;
  }

  public function validatePagination($page = 1, $perPage = 10): array
  {
    $validatedPage = $this->validatePage($page);
    $validatedPerPage = $this->validateLimit($perPage, 10, 100);

    $offset = ($validatedPage - 1) * $validatedPerPage;

    return [
      'page' => $validatedPage,
      'per_page' => $validatedPerPage,
      'offset' => $offset
    ];
  }

  public function validatePage($page): int
  {
    $intPage = (int) ($page ?? 1);

    // Strona musi być co najmniej 1
    return max(1, $intPage);
  }

  public function validateNumericParameter(
    $value,
    string $paramName,
    int $min = 0,
    int $max = PHP_INT_MAX,
    ?int $default = null
  ): int {
    if ($value === null || $value === '') {
      if ($default !== null) {
        return $default;
      }
      throw new \InvalidArgumentException("Parameter '{$paramName}' is required");
    }

    $intValue = (int) $value;

    if ($intValue < $min) {
      throw new \InvalidArgumentException("Parameter '{$paramName}' must be at least {$min}, got: {$intValue}");
    }

    if ($intValue > $max) {
      throw new \InvalidArgumentException("Parameter '{$paramName}' cannot exceed {$max}, got: {$intValue}");
    }

    return $intValue;
  }
}
