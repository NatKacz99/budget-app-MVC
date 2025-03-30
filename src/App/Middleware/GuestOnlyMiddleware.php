<?php

declare(strict_types=1);

namespace App\Middleware;

use Framework\Contracts\MiddlewareInterface;

class GuestOnlyMiddleware implements MiddlewareInterface
{
  public function process(callable $next)
  {
    if (!empty($SESSION['user'])) {
      redirectTo('/');
    }
    $next();
  }
}
