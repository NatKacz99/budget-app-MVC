<?php

declare(strict_types=1);

namespace App\Controllers;

use Framework\TemplateEngine;
use App\Services\{SettingsService};

class TransactionController
{
  public function __construct(
    private TemplateEngine $view,
    private SettingsService $settingsService
  ) {}
}