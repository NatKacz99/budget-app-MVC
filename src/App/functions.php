<?php

declare(strict_types=1);

function dd(mixed $value)
{
  if (getenv('APP_ENV') !== 'production') {
    echo "<pre>";
    var_dump($value);
    echo "</pre>";
    die();
  }
}

function e($value): string
{
  return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8', false);
}
