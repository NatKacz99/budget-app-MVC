<?php include $this->resolve("partials/_headerAddExpense.php"); ?>

<body>
  <?php include $this->resolve("partials/_navigation.php"); ?>

  <article>
    <form method="post">
      <?php include $this->resolve('partials/_csrf.php') ?>
      <div class="container-outside">
        <div class="container-inside">
          <h2>Wprowadź dane</h2>
          <div>
            <label for="category">
              <select class="category" name="category" id="categorySelect" data-limits='<?= json_encode($limitsExpenses) ?>'>
                <option value="" selected disabled>Wybierz kategorię wydatku</option>
                <?php foreach ($categoriesExpenses as $categoryExpenses): ?>
                  <option value="<?= $categoryExpenses['name'] ?>"
                    <?php echo isset($oldFormData[$categoryExpenses['name']]) && $oldFormData[$categoryExepenses['name']] === $categoryExpenses['name'] ? 'selected' : ''; ?>>
                    <?= $categoryExpenses['name'] ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </label>
          </div>
          <?php if (array_key_exists('category', $errors)) : ?>
            <div class="error" style="color: red">
              <?php echo e($errors['category'][0]); ?>
            </div>
          <?php endif; ?>

          <div class="input-group">
            <span class="icon-container"><i class="icon-pencil"></i></span>
            <input value="<?php echo e($oldFormData['price'] ?? '') ?>" type="text" name="price" class="form-control" placeholder="Kwota">
          </div>
          <?php if (array_key_exists('price', $errors)) : ?>
            <div class="error" style="color: red">
              <?php echo e($errors['price'][0]); ?>
            </div>
          <?php endif; ?>

          <?php $current_day = date('Y-m-d'); ?>
          <div class="input-group">
            <span class="icon-container"><i class="icon-calendar"></i></span>
            <input type="text" name="date" class="datepicker form-control" value="<?php echo $current_day ?>">
          </div>


          <div>
            <label for="payment-method">
              <select class="payment-method" name="paymentMethod">
                <option selected disabled>Wybierz sposób płatności</option>
                <?php foreach ($categoriesPaymentMethods as $categoryPaymentMethods): ?>
                  <option value="<?= $categoryPaymentMethods['name'] ?>"
                    <?php echo isset($oldFormData[$categoryPaymentMethods['name']]) && $oldFormData[$categoryPaymentMethods['name']] === $categoryPaymentMethods['name'] ? 'selected' : ''; ?>>
                    <?= $categoryPaymentMethods['name'] ?>
                  </option>
                <?php endforeach; ?>
              </select>
            </label>
          </div>
          <?php if (array_key_exists('paymentMethod', $errors)) : ?>
            <div class="error" style="color: red">
              <?php echo e($errors['paymentMethod'][0]); ?>
            </div>
          <?php endif; ?>

          <div class="input-group">
            <span class="icon-container"><i class="icon-pencil"></i></span>
            <input value="<?php echo e($oldFormData['comment'] ?? '') ?>" type="text" name="comment" class="form-control" placeholder="Komentarz (opcjonalnie)">
          </div>
          <?php if (array_key_exists('comment', $errors)) : ?>
            <div class="error" style="color: red">
              <?php echo e($errors['comment'][0]); ?>
            </div>
          <?php endif; ?>

          <div class="buttons">
            <input type="submit" value="Dodaj">
            <input type="submit" value="Anuluj">
          </div>

        </div>
        <div class="limit-container">
          <div class="limit-panel">
            <div id="limitInfo" style="padding: 0">
              <h3 style="text-align: center">Miesięczny limit</h3>
              <p id="limitText"></p>
            </div>
          </div>
          <div class="limit-panel">
            <div id="limitInfo" style="padding: 0">
              <h3 style="text-align: center">Kwota wykorzystana <br /> w wybranym miesiącu <br />
                w ramach tej kategorii</h3>
              <p id="usedAmountText">Wybierz kategorię aby zobaczyć wykorzystaną kwotę</p>
            </div>
          </div>
          <div class="limit-panel">
            <div id="limitInfo" style="padding: 0">
              <h3 style="text-align: center">Pozostała kwota z limitu</h3>
              <p id="limitBalance"></p>
            </div>
          </div>
          <div>
          </div>
    </form>

  </article>

</body>

</html>