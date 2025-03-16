<!DOCTYPE html>

<html lang="pl">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title>Dodaj przychód</title>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
  <link href="/css/bootstrap.min.css" rel="stylesheet">
  <script src="/assets/js/bootstrap.min.js"></script>

  <link rel="stylesheet" href="/css/cap.css">
  <link rel="stylesheet" href="/assets/style_add_expense.css">

  <script>
    $(function() {
      $(".datepicker").datepicker({
        dateFormat: "yy-mm-dd"
      });

      $(".menu-mobile__collapsible").on("click", function() {
        let $menuList = $(".menu-mobile__list");

        if ($menuList.hasClass("hidden")) {
          $menuList.removeClass("hidden");
        } else {
          $menuList.addClass("hidden");
        }
      });
    });
  </script>
</head>