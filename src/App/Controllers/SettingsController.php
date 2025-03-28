<?php

declare(strict_types=1);

namespace App\Controllers;

use Framework\TemplateEngine;
use App\Services\{SettingsService};

class SettingsController
{
  public function __construct(
    private TemplateEngine $view,
    private SettingsService $settingsService
  ) {
    public function editView(array $params){
        dd($params);
    }
  }
}