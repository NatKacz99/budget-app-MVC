<!DOCTYPE html>

<html lang="pl">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title><?php echo e($title); ?> - Aplikacja budżetowa</title>

  <link rel="stylesheet" href="/assets/style.css">

</head>

<body>
  <main>
    <header>
      <h1>Twój osobisty budżet</h1>
    </header>
    <section class="background-piggybank">
      <h2>Tutaj możesz samodzielnie zarządzać oraz kontrolować swoje przychody i wydatki. Dowiesz się, ile pieniędzy
        zaoszczędziłeś lub
        czy nie jesteś "na minusie".</h2>

      <div class="buttons-logging-registration">
        <div>
          <p>Masz już konto?</p>
          <a href="/login"><button id="log-in">Zaloguj się</button></a>
        </div>

        <div class="container__sign-up">
          <p>Nie masz konta?</p>
          <a href="/register"><button id="sign-up">Zarejestruj się</button></a>
        </div>
      </div>
    </section>
  </main>
</body>