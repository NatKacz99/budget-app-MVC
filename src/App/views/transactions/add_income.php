<?php include $this->resolve("partials/_headerAddIncome.php"); ?>

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
            <input type="text" name="date" class="datepicker form-control" value="<?php echo $current_day; ?>">
          </div>

          <div>
            <label for="category">
              <select class="category" name="category">
                <option selected disabled>Wybierz kategorię przychodu</option>
                <?php foreach ($categories as $category): ?>
                  <option value="<?= $category['name'] ?>"
                    <?php echo isset($oldFormData[$category['name']]) && $oldFormData[$category['name']] === $category['name'] ? 'selected' : ''; ?>>
                    <?= $category['name'] ?>
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
      </div>
    </form>

  </article>

</body>

</html>