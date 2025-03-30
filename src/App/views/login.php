<?php include $this->resolve("partials/_headerLogin.php"); ?>

<body class="d-flex align-items-center py-4 justify-content-center">
  <div class="container">
    <div class="home-page-link" style="margin-bottom: 80px">
      <?php include $this->resolve("partials/_navigationLoginRegister.php"); ?>
    </div>
    <div>
      <main class="form-signin w-100 m-auto" style="max-width: 400px;">

        <form method="post" action="">
          <?php include $this->resolve('partials/_csrf.php') ?>

          <h1 class="text-center pb-4">Logowanie</h1>

          <div class="container">
            <div class="input-group">
              <span class="input-group-text">
                <i class="icon-mail-alt"></i>
              </span>
              <div class="form-floating">
                <input value="<?php echo e($oldFormData['email'] ?? '') ?>" type="text" name="email" class="form-control mb-1" id="floatingInput" placeholder="E-mail">
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
                <input value="<?php echo e($oldFormData['password'] ?? '') ?>" type="password" name="password" class="form-control" id="floatingPassword" placeholder="Password">
                <label for="floatingPassword">Hasło</label>
              </div>
            </div>
            <?php if (array_key_exists('password', $errors)) : ?>
              <div class="error">
                <?php echo e($errors['password'][0]); ?>
              </div>
            <?php endif; ?>

            <div class="form-check text-start my-3">
              <input class="form-check-input justify-content-center" type="checkbox" value="remember-me"
                id="flexCheckDefault">
              <label class="form-check-label" for="flexCheckDefault">
                Zapamiętaj mnie
              </label>
            </div>

            <button class="btn btn-primary w-100 py-2" type="submit">Zaloguj</button>

          </div>
        </form>


      </main>
    </div>
  </div>

</body>

</html>