<?php include $this->resolve("partials/_headerRegister.php"); ?>

<body class="d-flex align-items-center py-4 justify-content-center">
  <div class="container">
    <div class="home-page-link" style="margin-bottom: 80px;">
      <?php include $this->resolve("partials/_navigationLoginRegister.php"); ?>
    </div>
    <div>
      <main class="form-signin w-100 m-auto" style="max-width: 400px;">
        <form method="post" action="">
          <?php include $this->resolve('partials/_csrf.php') ?>
          <h1 class="text-center pb-4">Rejestracja</h1>

          <div class="container">
            <div class="input-group">
              <span class="input-group-text">
                <i class="icon-user"></i>
              </span>
              <div class="form-floating">
                <input value="<?php echo e($oldFormData['name'] ?? ''); ?>" type="text" name="name" class="form-control mb-2" id="floatingInput" placeholder="Name">
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
                <input value="<?php echo e($oldFormData['email'] ?? ''); ?>" type="text" name="email" class="form-control mb-2" id="floatingInput" placeholder="E-mail">
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

            <div class="g-recaptcha" data-sitekey="6LcXHowqAAAAABP82-rWyDMZssMhRD3emj__Huyk" style="margin-top: 16px"></div>
            <br />
            <button class="btn btn-primary w-100 py-2 mt-4" type="submit">Zarejestruj się</button>
            <?php if (isset($_SESSION['success_message'])) : ?>
              <div class="alert alert-success mt-3 text-center text-justify">
                <?php echo $_SESSION['success_message']; ?>
              </div>
              <?php unset($_SESSION['success_message']); ?>
            <?php endif; ?>
          </div>
        </form>
      </main>
    </div>
  </div>

  <?php if (isset($_SESSION['debug_info'])): ?>
    <div style="background: yellow; padding: 20px; margin: 20px; border: 2px solid red;">
      <h2>🐛 INFORMACJE DEBUGOWANIA:</h2>
      <pre style="background: white; padding: 10px;">
<?php
    foreach ($_SESSION['debug_info'] as $info) {
      echo $info . "\n";
    }
?>
        </pre>
    </div>
    <?php unset($_SESSION['debug_info']); ?>
  <?php endif; ?>
</body>

</html>