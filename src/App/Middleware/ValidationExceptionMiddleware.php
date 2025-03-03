<?php

declare(strict_types=1);

namespace App\Middleware;

use Framework\Contracts\MiddlewareInterface;
use Framework\Exceptions\ValidationException;

class ValidationExceptionMiddleware implements MiddlewareInterface
{
  public function process(callable $next)
  {
    try {
      $next();
    } catch (ValidationException $e) {
      $_SESSION['errors'] = $e->errors;
      $_SESSION['oldFormData'] = $_POST;

      $referer = $_SERVER['HTTP_REFERER'];
      redirectTo($referer);
    }
  }
}
