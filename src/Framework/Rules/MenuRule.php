<?php

declare(strict_types=1);

namespace Framework\Rules;

use Framework\Contracts\RuleInterface;

class MenuRule implements RuleInterface
{
  public function validate(array $data, string $field, array $params): bool
  {
    $value = $data[$field] ?? null;

    if ($value === null) {
      return false;
    }

    return (!in_array($value, $params));
  }

  public function getMessage(array $data, string $field, array $params): string
  {
    return "Nieprawidłowy wybór.";
  }
}
