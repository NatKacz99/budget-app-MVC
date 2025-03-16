<?php include $this->resolve("partials/_headerAddExpense.php"); ?>

<body>
  <?php include $this->resolve("partials/_navigation.php"); ?>

  <article>
    <form method="post">
      <?php include $this->resolve('partials/_csrf.php') ?>
      <h2>Wprowadź dane</h2>
      <div class="container-outside">
        <div class="container-inside">

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

          <div>
            <label for="category">
              <select class="category" name="category">
                <option selected disabled>Wybierz kategorię wydatku</option>
                <?php
                foreach ($all_categories_result as $row) {
                  echo '<option>' . htmlspecialchars($row['name']) . '</option>';
                }
                ?>
              </select>
            </label>
            <div class="error" style="color: red">
              <?php

              if (isset($_SESSION['error_category'])) {
                echo $_SESSION['error_category'];
                unset($_SESSION['error_category']);
              }

              ?>
            </div>
          </div>

          <div class="input-group">
            <span class="icon-container"><i class="icon-pencil"></i></span>
            <input type="text" name="comment" class="form-control" placeholder="Komentarz (opcjonalnie)">
          </div>

          <div class="buttons">
            <input type="submit" value="Dodaj">
            <input type="submit" value="Anuluj">
          </div>

        </div>
      </div>
    </form>

  </article>

</body>

</html>