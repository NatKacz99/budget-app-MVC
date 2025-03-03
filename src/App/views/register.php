<?php include $this->resolve("partials/_headerRegister.php"); ?>

<body class="d-flex align-items-center py-4 justify-content-center">

  <main class="form-signin w-100 m-auto" style="max-width: 400px;">
    <form method="post" action="/register">
      <h1 class="text-center pb-4">Rejestracja</h1>

      <div class="container">
        <div class="input-group">
          <span class="input-group-text">
            <i class="icon-user"></i>
          </span>
          <div class="form-floating">
            <input value="<?php echo e($oldFormData['name'] ?? ''); ?>" type="text" name="name" class="form-control mb-2" class="floatingInput" placeholder="Name" mb-2>
            <label for="floatingInput">Imię</label>
          </div>
        </div>
        <?php if (array_key_exists('name', $errors)) : ?>
          <div class="error">
            <?php echo e($errors['name'][0]); ?>
          </div>
        <?php endif; ?>

        <div class="input-group">
          <span class="input-group-text">
            <i class="icon-mail-alt"></i>
          </span>
          <div class="form-floating">
            <input value="<?php echo e($oldFormData['email'] ?? ''); ?>" type="text" name="email" class="form-control mb-2" class="floatingInput" placeholder="E-mail">
            <label for="floatingInput">Adres e-mail</label>
          </div>
        </div>
        <?php if (array_key_exists('email', $errors)) : ?>
          <div class="error">
            <?php echo e($errors['email'][0]); ?>
          </div>
        <?php endif; ?>

        <div class="input-group">
          <span class="input-group-text">
            <i class="icon-lock-filled"></i>
          </span>
          <div class="form-floating">
            <input value="<?php echo e($oldFormData['password'] ?? ''); ?>" type="password" name="password" class="form-control mb-2" id="password1"
              placeholder="Password">
            <label for="floatingPassword">Hasło</label>
          </div>
        </div>
        <?php if (array_key_exists('password', $errors)) : ?>
          <div class="error">
            <?php echo e($errors['password'][0]); ?>
          </div>
        <?php endif; ?>

        <div class="input-group">
          <span class="input-group-text">
            <i class="icon-lock-filled"></i>
          </span>
          <div class="form-floating">
            <input value="<?php echo e($oldFormData['confirmPassword'] ?? ''); ?>" type="password" name="confirmPassword" class="form-control mb-2" id="password2" placeholder="Password">
            <label for="floatingPassword">Powtórz hasło</label>
          </div>
        </div>
        <?php if (array_key_exists('confirmPassword', $errors)) : ?>
          <div class="error">
            <?php echo e($errors['confirmPassword'][0]); ?>
          </div>
        <?php endif; ?>

        <br />

        <button class="btn btn-primary w-100 py-2 mt-4" type="submit">Zarejestruj się</button>
      </div>

    </form>
    <div class="text-center mt-3">
      <?php
      if (isset($_SESSION['success_message'])) {
        echo '<div class="alert alert-success">' . $_SESSION['success_message'] . '</div>';
        unset($_SESSION['success_message']);
      }
      ?>
    </div>
  </main>
</body>