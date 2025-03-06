<?php

declare(strict_types=1);

namespace App\Controllers;

use Framework\TemplateEngine;
use App\Services\{ValidatorService, UserService};

class AuthController
{
  public function __construct(
    private TemplateEngine $view,
    private ValidatorService $validatorService,
    private UserService $userService
  ) {}

  public function registerView()
  {
    echo $this->view->render("register.php");
  }

  public function register()
  {
    $this->validatorService->validateRegister($_POST);

    $this->userService->isEmailTaken($_POST['email']);

    $this->userService->create($_POST);

    $_SESSION['success_message'] = 'Rejestracja przebiegła pomyślnie. Możesz zalogować się na swoje konto.&nbsp;<a href="/login" style="text-decoration: none;">[Zaloguj się.]</a>';

    redirectTo('/register');
  }

  public function loginView()
  {
    echo $this->view->render("login.php");
  }

  public function mainMenuView()
  {
    echo $this->view->render("main_menu.php");
  }

  public function login()
  {
    $this->validatorService->validateLogin($_POST);

    $this->userService->login($_POST);

    redirectTo('/main_menu');
  }
}
