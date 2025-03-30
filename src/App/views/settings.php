<?php include $this->resolve("partials/_headerSettings.php"); ?>

<body>
  <?php include $this->resolve("partials/_navigation.php"); ?>

  <main>
    <h2>Ustawienia</h2>
    <div class="container">
      <section>
        <h3 style="text-align: center">Edycja nazwy kategorii</h3>
        <div class="edit-category-name">
          <button type="button" class="edit" style="flex: 1" data-toggle="modal" data-target="#modalEditIncomes">
            Przychody
          </button>
          <button type="button" class="edit" style="flex: 1" data-toggle="modal" data-target="#modalEditExpenses">
            Wydatki
          </button>
          <button type="button" class="edit" style="flex: 1" data-toggle="modal" data-target="#modalEditPaymentMethods">
            Sposoby płatności
          </button>
        </div>

        <div class="modal fade" id="modalEditIncomes" tabindex="-1" role="dialog" aria-labelledby="modalEditIncomesLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="modalEditIncomesLabel" style="text-align: center">Wybierz kategorię do edycji i wpisz nową nazwę</h5>
              </div>
              <div class="modal-body">
                <form method="post" action="/settings">
                  <?php include $this->resolve('partials/_csrf.php') ?>
                  <input type="text" name="changeCategoryIncome">
                  <?php if (!empty($categoriesIncomes)) : ?>
                    <?php foreach ($categoriesIncomes as $categoryIncome) : ?>
                      <div class="form-check">
                        <input class="form-check-input" type="radio" name="categoryIncome" value="<?php echo e($categoryIncome['name']); ?>" id="<?php echo e($categoryIncome['name']); ?>">
                        <label class="form-check-label" for="<?php echo e($categoryIncome['name']); ?>">
                          <?php echo e($categoryIncome['name']); ?>
                        </label>
                      </div>
                    <?php endforeach; ?>
                  <?php endif; ?>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Zamknij</button>
                <button type="submit" class="btn btn-success">Zapisz zmiany</button>
              </div>
              </form>
            </div>
          </div>
        </div>

        <div class="modal fade" id="modalEditExpenses" tabindex="-1" role="dialog" aria-labelledby="modalEditExpensesLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="modalEditExpensesLabel" style="text-align: center">Wybierz kategorię do edycji i wpisz nową nazwę</h5>
              </div>
              <div class="modal-body">
                <form method="post" action="/settings">
                  <?php include $this->resolve('partials/_csrf.php') ?>
                  <input type="text" name="changeCategoryExpense">
                  <?php if (!empty($categoriesExpenses)) : ?>
                    <?php foreach ($categoriesExpenses as $categoryExpense) : ?>
                      <div class="form-check">
                        <input class="form-check-input" type="radio" name="categoryExpense" value="<?php echo e($categoryExpense['name']); ?>" id="<?php echo e($categoryExpense['name']); ?>">
                        <label class="form-check-label" for="<?php echo e($categoryExpense['name']); ?>">
                          <?php echo e($categoryExpense['name']); ?>
                        </label>
                      </div>
                    <?php endforeach; ?>
                  <?php endif; ?>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Zamknij</button>
                <button type="submit" class="btn btn-success">Zapisz zmiany</button>
              </div>
              </form>
            </div>
          </div>
        </div>

        <div class="modal fade" id="modalEditPaymentMethods" tabindex="-1" role="dialog" aria-labelledby="modalEditPaymentMethodsLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="modalEditPaymentMethodsLabel" style="text-align: center">Wybierz kategorię do edycji i wpisz nową nazwę</h5>
              </div>
              <div class="modal-body">
                <form method="post" action="/settings">
                  <?php include $this->resolve('partials/_csrf.php') ?>
                  <input type="text" name="changeCategoryPaymentMethod">
                  <?php if (!empty($categoriesPaymentMethods)) : ?>
                    <?php foreach ($categoriesPaymentMethods as $categoryPaymentMethod) : ?>
                      <div class="form-check">
                        <input class="form-check-input" type="radio" name="categoryPaymentMethod" value="<?php echo e($categoryPaymentMethod['name']); ?>" id="<?php echo e($categoryPaymentMethod['name']); ?>">
                        <label class="form-check-label" for="<?php echo e($categoryPaymentMethod['name']); ?>">
                          <?php echo e($categoryPaymentMethod['name']); ?>
                        </label>
                      </div>
                    <?php endforeach; ?>
                  <?php endif; ?>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Zamknij</button>
                <button type="submit" class="btn btn-success">Zapisz zmiany</button>
              </div>
              </form>
            </div>
          </div>
        </div>
        <hr>
        <h3 style="text-align: center">Dodanie nowej kategorii</h3>
        <div class="added-category-name">
          <button type="button" class="added" style="flex: 1" data-toggle="modal" data-target="#modalAddIncomes">
            Przychody
          </button>
          <button type="button" class="added" style="flex: 1" data-toggle="modal" data-target="#modalAddExpenses">
            Wydatki
          </button>
          <button type="button" class="added" style="flex: 1" data-toggle="modal" data-target="#modalAddPaymentMethods">
            Sposoby płatności
          </button>
        </div>

        <div class="modal fade" id="modalAddIncomes" tabindex="-1" role="dialog" aria-labelledby="modalAddIncomesLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="modalAddIncomesLabel" style="text-align: center">Wpisz nazwę nowej kategorii</h5>
              </div>
              <div class="modal-body">
                <form method="post" action="/settings">
                  <?php include $this->resolve('partials/_csrf.php') ?>
                  <input type="text" name="addedCategoryIncome">
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Zamknij</button>
                <button type="submit" class="btn btn-success">Zapisz zmiany</button>
              </div>
              </form>
            </div>
          </div>
        </div>

        <div class="modal fade" id="modalAddExpenses" tabindex="-1" role="dialog" aria-labelledby="modalAddExpensesLabel" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="modalAddExpensesLabel" style="text-align: center">Wpisz nazwę nowej kategorii</h5>
              </div>
              <div class="modal-body">
                <form method="post" action="/settings">
                  <?php include $this->resolve('partials/_csrf.php') ?>
                  <input type="text" name="addedCategoryExpense">
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Zamknij</button>
                <button type="submit" class="btn btn-success">Zapisz zmiany</button>
              </div>
              </form>
            </div>
          </div>
        </div>

        <div class="modal fade" id="modalAddPaymentMethods" tabindex="-1" role="dialog" aria-labelledby="modalAddPaymentMethods" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="modalAddPaymentmethods" style="text-align: center">Wpisz nazwę nowej kategorii</h5>
              </div>
              <div class="modal-body">
                <form method="post" action="/settings">
                  <?php include $this->resolve('partials/_csrf.php') ?>
                  <input type="text" name="addedPaymentMethod">
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Zamknij</button>
                <button type="submit" class="btn btn-success">Zapisz zmiany</button>
              </div>
              </form>
            </div>
          </div>
        </div>
        <hr>
        <h3 style="text-align: center">Usunięcie kategorii</h3>
        <div class="delete-category-name">
          <button type="button" class="deleted" style="flex: 1" data-toggle="modal" data-target="#modalDeleteIncomes">
            Przychody
          </button>
          <button type="button" class="deleted" style="flex: 1" data-toggle="modal" data-target="#modalDeleteExpenses">
            Wydatki
          </button>
          <button type="button" class="deleted" style="flex: 1" data-toggle="modal" data-target="#modalDeletePaymentMethods">
            Sposoby płatności
          </button>
        </div>

        <div class="modal fade" id="modalDeleteIncomes" tabindex="-1" role="dialog" aria-labelledby="modalDeleteIncomes" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="modalDeleteIncomesLabel" style="text-align: center">Wybierz kategorię do usunięcia</h5>
              </div>
              <div class="modal-body">
                <form method="post" action="/settings">
                  <input type="hidden" name="_METHOD" value="DELETE">
                  <?php include $this->resolve('partials/_csrf.php') ?>
                  <?php if (!empty($categoriesIncomes)) : ?>
                    <?php foreach ($categoriesIncomes as $index => $categoryIncome) : ?>
                      <div class="form-check">
                        <input class="form-check-input" type="radio" name="categoryIncomeDeleted" value="<?php echo e($categoryIncome['name']); ?>" id="income_<?php echo e($index); ?>_<?php echo e($categoryIncome['name']); ?>">
                        <label class="form-check-label" for="income_<?php echo e($index); ?>_<?php echo e($categoryIncome['name']); ?>">
                          <?php echo e($categoryIncome['name']); ?>
                        </label>
                      </div>
                    <?php endforeach; ?>
                  <?php endif; ?>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Zamknij</button>
                <button type="submit" class="btn btn-success">Zapisz zmiany</button>
              </div>
              </form>
            </div>
          </div>
        </div>

        <div class="modal fade" id="modalDeleteExpenses" tabindex="-1" role="dialog" aria-labelledby="modalDeleteExpenses" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="modalDeleteExpensesLabel" style="text-align: center">Wybierz kategorię do usunięcia</h5>
              </div>
              <div class="modal-body">
                <form method="post" action="/settings">
                  <input type="hidden" name="_METHOD" value="DELETE">
                  <?php include $this->resolve('partials/_csrf.php') ?>
                  <?php if (!empty($categoriesExpenses)) : ?>
                    <?php foreach ($categoriesExpenses as $index => $categoryExpense) : ?>
                      <div class="form-check">
                        <input class="form-check-input" type="radio" name="categoryExpenseDeleted" value="<?php echo e($categoryExpense['name']); ?>" id="income_<?php echo e($index); ?>_<?php echo e($categoryExpense['name']); ?>">
                        <label class="form-check-label" for="income_<?php echo e($index); ?>_<?php echo e($categoryExpense['name']); ?>">
                          <?php echo e($categoryExpense['name']); ?>
                        </label>
                      </div>
                    <?php endforeach; ?>
                  <?php endif; ?>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Zamknij</button>
                <button type="submit" class="btn btn-success">Zapisz zmiany</button>
              </div>
              </form>
            </div>
          </div>
        </div>

        <div class="modal fade" id="modalDeletePaymentMethods" tabindex="-1" role="dialog" aria-labelledby="modalDeletePaymentMethods" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="modalDeletePaymentMethodsLabel" style="text-align: center">Wybierz kategorię do usunięcia</h5>
              </div>
              <div class="modal-body">
                <form method="post" action="/settings">
                  <input type="hidden" name="_METHOD" value="DELETE">
                  <?php include $this->resolve('partials/_csrf.php') ?>
                  <?php if (!empty($categoriesPaymentMethods)) : ?>
                    <?php foreach ($categoriesPaymentMethods as $index => $categoryPaymentMethod) : ?>
                      <div class="form-check">
                        <input class="form-check-input" type="radio" name="categoryPaymentMethodDeleted" value="<?php echo e($categoryPaymentMethod['name']); ?>" id="paymentMethod_<?php echo e($index); ?>_<?php echo e($categoryPaymentMethod['name']); ?>">
                        <label class="form-check-label" for="paymentMethod_<?php echo e($index); ?>_<?php echo e($categoryPaymentMethod['name']); ?>">
                          <?php echo e($categoryPaymentMethod['name']); ?>
                        </label>
                      </div>
                    <?php endforeach; ?>
                  <?php endif; ?>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Zamknij</button>
                <button type="submit" class="btn btn-success">Zapisz zmiany</button>
              </div>
              </form>
            </div>
          </div>
        </div>
        <hr>
        <h3 style="text-align: center">Edycja konta użytkownika</h3>
        <button type="button" class="editData" style="flex: 1; width: 100%" data-toggle="modal" data-target="#modalEditUserData">
          Edytuj dane użytkownika
        </button>
        <button type="button" class="deleteAccount" style="flex: 1; width: 100%">
          <i class="icon-cancel"></i>
          Usuń swoje konto
        </button>
        <?php if (isset($_SESSION['name_error_message'])) : ?>
          <div class="error" style="color: red">
            <?php echo $_SESSION['name_error_message']; ?>
          </div>
          <?php unset($_SESSION['name_error_message']); ?>
        <?php endif; ?>
        <?php if (isset($_SESSION['email_error_message'])) : ?>
          <div class="error" style="color: red">
            <?php echo $_SESSION['email_error_message']; ?>
          </div>
          <?php unset($_SESSION['email_error_message']); ?>
        <?php endif; ?>
        <?php if (isset($_SESSION['confirm_password_error'])) : ?>
          <div class="error" style="color: red">
            <?php echo $_SESSION['confirm_password_error']; ?>
          </div>
          <?php unset($_SESSION['confirm_password_error']); ?>
        <?php endif; ?>
        <?php if (isset($_SESSION['password_error'])) : ?>
          <div class="error" style="color: red">
            <?php echo $_SESSION['password_error']; ?>
          </div>
          <?php unset($_SESSION['password_error']); ?>
        <?php endif; ?>
        <?php if (isset($_SESSION['password_match_error'])) : ?>
          <div class="error" style="color: red">
            <?php echo $_SESSION['password_match_error']; ?>
          </div>
          <?php unset($_SESSION['password_match_error']); ?>
        <?php endif; ?>

        <div class="modal fade" id="modalEditUserData" tabindex="-1" role="dialog" aria-labelledby="modalEditUserData" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" id="modalEditUserDataLabel" style="text-align: center">Wprowadź nowe dane</h5>
              </div>
              <div class="modal-body">
                <form method="post" action="/settings">
                  <?php include $this->resolve('partials/_csrf.php') ?>
                  <div class="input-group">
                    <span class="icon-container"><i class="icon-user"></i></span>
                    <input value="<?php echo e($oldFormData['name'] ?? '') ?>" type="text" name="name" class="form-control" placeholder="Imię">
                  </div>
                  <div class="input-group">
                    <span class="icon-container"><i class="icon-mail-alt"></i></span>
                    <input value="<?php echo e($oldFormData['email'] ?? '') ?>" type="text" name="email" class="form-control" placeholder="Adres e-mail">
                  </div>
                  <div class="input-group">
                    <span class="icon-container"><i class="icon-lock-filled"></i></span>
                    <input value="<?php echo e($oldFormData['password'] ?? '') ?>" type="password" name="password" class="form-control" id="password1" placeholder="Nowe hasło">
                  </div>
                  <div class="input-group">
                    <span class="icon-container"><i class="icon-lock-filled"></i></span>
                    <input value="<?php echo e($oldFormData['confirmPassword'] ?? '') ?>" type="password" name="confirmPassword" class="form-control" id="password2" placeholder="Powtórz hasło">
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Zamknij</button>
                    <button type="submit" class="btn btn-success">Zapisz zmiany</button>
                  </div>
                </form>
              </div>
            </div>
          </div>

      </section>
      <div>
  </main>
</body>