<?php

declare(strict_types=1);

namespace Framework\Rules;

use Framework\Contracts\RuleInterface;

class PriceRule implements RuleInterface
{
  public function validate(array $data, string $field, array $params): bool
  {
    $price = str_replace(',', '.', $data[$field]);

    return (bool) is_numeric($price) && !empty($price);
  }

  public function getMessage(array $data, string $field, array $params): string
  {
    return "Podaj kwotę w formacie liczbowym.";
  }
}
