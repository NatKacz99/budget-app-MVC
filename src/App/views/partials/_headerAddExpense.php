<!DOCTYPE html>

<html lang="pl">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title>Dodaj wydatek</title>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
  <link href="./css/bootstrap.min.css" rel="stylesheet">
  <script src="/assets/js/bootstrap.min.js"></script>


  <link rel="stylesheet" href="/assets/style_add_expense.css">
  <link rel="stylesheet" href="./css/close.css">

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

    document.addEventListener('DOMContentLoaded', function() {
      const categorySelect = document.getElementById('categorySelect');
      const limitInfo = document.getElementById('limitInfo');
      const limitText = document.getElementById('limitText');

      if (!categorySelect) return;

      const limits = JSON.parse(categorySelect.getAttribute('data-limits') || '[]');

      categorySelect.addEventListener('change', function() {
        const selectedCategory = this.value;
        const categoryLimit = limits.find(limit => limit.name === selectedCategory);

        if (categoryLimit) {
          limitText.innerHTML = `<strong>Limit dla "${selectedCategory}":</strong> ${categoryLimit.expense_limit} zł`;
          limitInfo.style.display = 'block';
        } else {
          limitInfo.style.display = 'none';
        }
      });
    });
  </script>
</head>