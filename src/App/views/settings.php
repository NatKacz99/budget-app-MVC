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
      </section>
      <div>
  </main>
</body>