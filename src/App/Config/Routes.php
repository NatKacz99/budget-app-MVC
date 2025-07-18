<?php

declare(strict_types=1);

namespace App\Config;

use Framework\App;
use App\Controllers\{
  HomeController,
  AuthController,
  TransactionController,
  SettingsController,
  ErrorController,
};
use App\Middleware\{AuthRequiredMiddleware, GuestOnlyMiddleware};

function registerRoutes(App $app)
{
  $app->get('/', [HomeController::class, 'home']);
  $app->get('/register', [AuthController::class, 'registerView'])->add(GuestOnlyMiddleware::class);
  $app->post('/register', [AuthController::class, 'register'])->add(GuestOnlyMiddleware::class);
  $app->get('/login', [AuthController::class, 'loginView'])->add(GuestOnlyMiddleware::class);
  $app->post('/login', [AuthController::class, 'login'])->add(GuestOnlyMiddleware::class);
  $app->get('/main_menu', [AuthController::class, 'mainMenuView']);
  $app->get('/logout', [AuthController::class, 'logOut'])->add(AuthRequiredMiddleware::class);
  $app->get('/incomes', [TransactionController::class, 'createViewAddIncome']);
  $app->post('/incomes', [TransactionController::class, 'createAddIncome']);
  $app->get('/expenses', [TransactionController::class, 'createViewAddExpense']);
  $app->post('/expenses', [TransactionController::class, 'createAddExpense']);
  $app->get('/balance', [TransactionController::class, 'createViewShowBalance']);
  $app->post('/balance', [TransactionController::class, 'createShowBalance']);
  $app->get('/settings', [SettingsController::class, 'editView'])->add(AuthRequiredMiddleware::class);
  $app->post('/settings', [SettingsController::class, 'edit'])->add(AuthRequiredMiddleware::class);
  $app->delete('/settings', [SettingsController::class, 'delete'])->add(AuthRequiredMiddleware::class);
  $app->get('/api/limit', [TransactionController::class, 'setExpenseLimit']);
  $app->get('/api/monthly-expenses', [TransactionController::class, 'getMonthlyExpenseSum']);
  $app->get('/api/limit-balance', [TransactionController::class, 'getMonthlyLimitBalance']);

  $app->setErrorHandler([ErrorController::class, 'notFound']);
}
