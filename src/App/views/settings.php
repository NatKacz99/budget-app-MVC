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
                <form method="post">
                  <input type="text">
                  <?php if (!empty($categoriesIncomes)) : ?>
                    <?php foreach ($categoriesIncomes as $categoryIncome) : ?>
                      <div class="form-check">
                        <input class="form-check-input" type="radio" name="category" value="<?php echo e($categoryIncome['name']); ?>" id="<?php echo e($categoryIncome['name']); ?>">
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
    </div>
    </section>
    <div>
  </main>
</body>