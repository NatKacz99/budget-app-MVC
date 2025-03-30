<?php

declare(strict_types=1);

namespace App\Services;

use Framework\Validator;
use Framework\Rules\{
  NameRule,
  RequiredRule,
  EmailRule,
  MatchRule,
  PriceRule,
  MenuRule,
  LengthMaxRule
};

class ValidatorService
{
  private Validator $validator;

  public function __construct()
  {
    $this->validator = new Validator();

    $this->validator->add('name', new NameRule());
    $this->validator->add('required', new RequiredRule());
    $this->validator->add('email', new EmailRule());
    $this->validator->add('match', new MatchRule());
    $this->validator->add('price', new PriceRule());
    $this->validator->add('category', new MenuRule());
    $this->validator->add('lengthMax', new LengthMaxRule());
  }

  public function validateRegister(array $formData)
  {
    $this->validator->validate($formData, [
      'name' => ['required', 'name'],
      'email' => ['required', 'email'],
      'password' => ['required'],
      'confirmPassword' => ['required', 'match:password'],
    ]);
  }

  public function validateLogin(array $formData)
  {
    $this->validator->validate($formData, [
      'email' => ['required', 'email'],
      'password' => ['required']
    ]);
  }

  public function validateIncome(array $formData)
  {
    $this->validator->validate($formData, [
      'price' => ['required', 'price'],
      'category' => ['required', 'category'],
      'comment' => ['lengthMax:100']
    ]);
  }

  public function validateExpense(array $formData)
  {
    $this->validator->validate($formData, [
      'price' => ['required', 'price'],
      'paymentMethod' => ['required', 'category'],
      'category' => ['required', 'category'],
      'comment' => ['lengthMax:100']
    ]);
  }

  public function validateEdittingUserName(array $formData)
  {
    $this->validator->validate($formData, [
      'name' => ['name'],
    ]);
  }

  public function validateEdittingUserEmail(array $formData)
  {
    $this->validator->validate($formData, [
      'email' => ['email']
    ]);
  }

  public function validatePasswordEditting(array $formData)
  {
    $this->validator->validate($formData, [
      'confirmPassword' => ['match:password']
    ]);
  }
}
