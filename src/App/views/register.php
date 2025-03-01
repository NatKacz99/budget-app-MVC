<?php

if (isset($_POST['e-mail'])) {
  $all_OK = true;

  $password_hash = password_hash($password1, PASSWORD_DEFAULT);


  require_once "connect.php";
  mysqli_report(MYSQLI_REPORT_STRICT);

  try {

    $connection = new mysqli($host, $db_user, $db_password, $db_name);

    if ($connection->connect_errno != 0) {
      throw new Exception(mysqli_connect_errno());
    } else {
      $score_mails = $connection->query("SELECT id FROM users WHERE email = '$email'");
      if (!$score_mails) {
        throw new Exception($connection->error);
      }

      $how_many_mails = $score_mails->num_rows;
      if ($how_many_mails > 0) {
        $all_OK = false;
        $_SESSION['error_mail'] = "Istnieje już konto o podanym adresie e-mail.";
      }

      $result_id_expense = $connection->query("SELECT MAX(id) AS max_id FROM incomes_category_assigned_to_users");
      if (!$result_id_expense) {
        throw new Exception($connection->error);
      }

      $row = $result_id_expense->fetch_assoc();
      $max_id = $row['max_id'];

      if ($max_id !== null) {
        $next_id = $max_id + 1;
        $connection->query("ALTER TABLE incomes_category_assigned_to_users AUTO_INCREMENT = $next_id");
      }


      if ($all_OK == true) {
        if ($connection->query("INSERT INTO users VALUES(NULL, '$username', '$password_hash', '$email')")) {
          $user_id = $connection->insert_id;

          if ($user_id) {

            $insert_categories_expense_query = "INSERT INTO expenses_category_assigned_to_users (user_id, name) 
                                                 SELECT '$user_id', name FROM expenses_category_default";

            if (!$connection->query($insert_categories_expense_query)) {
              throw new Exception("Błąd podczas dodawania kategorii wydatków: " . $connection->error);
            }

            $insert_payment_query = "INSERT INTO payment_methods_assigned_to_users (user_id, name) 
                    SELECT '$user_id', name FROM payment_methods_default";

            if (!$connection->query($insert_payment_query)) {
              throw new Exception("Błąd podczas dodawania metody płatności: " . $connection->error);
            }

            $insert_categories_income_query = "INSERT INTO incomes_category_assigned_to_users (user_id, name)
                                                SELECT '$user_id', name FROM incomes_category_default";

            if (!$connection->query($insert_categories_income_query)) {
              throw new Exception("Błąd podczas dodawania kategorii przychodów: " . $connection->error);
            }
          } else {
            throw new Exception("Błąd: Użytkownik nie został dodany.");
          }

          $_SESSION['success_message'] = 'Rejestracja przebiegła pomyślnie. Możesz zalogować się na swoje konto.&nbsp;<a href="strona_logowania.php" style="text-decoration: none;">[Zaloguj się.]</a>';
        } else {
          throw new Exception("Błąd: Użytkownik nie został dodany.");
        }
      }

      $connection->close();
    }
  } catch (Exception $error) {

    echo '<span style="color: red">Błąd serwera! Przepraszamy za niedogodności i prosimy o rejestrację w innym możliwym terminie.</span>';
    echo $error->getMessage();
  }
}

?>

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