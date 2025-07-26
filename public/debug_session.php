<?php
session_start();
echo "<h1>Aktualna sesja:</h1>";
echo "<pre>";
print_r($_SESSION);
echo "</pre>";

echo "<h2>Wyczyść sesję:</h2>";
echo '<a href="?clear=1" style="background: red; color: white; padding: 10px; text-decoration: none;">WYCZYŚĆ SESJĘ</a>';

if (isset($_GET['clear'])) {
  session_destroy();
  echo "<p style='color: green;'>Sesja wyczyszczona! <a href='debug_session.php'>Odśwież</a></p>";
}
