<?php

declare(strict_types=1);

namespace Framework\Rules;

use Framework\Contracts\RuleInterface;

class AmountCharactersRule implements RuleInterface
{
  public function validate(array $data, string $field, array $params): bool
  {
    return (bool) strlen($data[$field]) >= 8;
  }

  public function getMessage(array $data, string $field, array $params): string
  {
    return "Hasło musi zawierać minimum 8 znaków.";
  }
}
