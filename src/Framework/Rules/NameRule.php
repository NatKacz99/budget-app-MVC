<?php

declare(strict_types=1);

namespace Framework\Rules;

use Framework\Contracts\RuleInterface;

class NameRule implements RuleInterface
{
  public function validate(array $data, string $field, array $params): bool
  {
    return (bool) ctype_alnum($data[$field]);
  }

  public function getMessage(array $data, string $field, array $params): string
  {
    return "Nazwa może się składać tylko z liter i cyfr (bez polskich znaków).";
  }
}
