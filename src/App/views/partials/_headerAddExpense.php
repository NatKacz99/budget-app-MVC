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
  <script src="/assets/js/limit.js"></script>


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

      async function updateLimitInfo() {
        const selectedCategory = categorySelect.value;

        if (!selectedCategory || selectedCategory === '' || selectedCategory === 'Wybierz kategorię wydatku') {
          limitInfo.style.display = 'block';
          limitText.innerHTML = 'Wymagany wybór kategorii';
          return;
        }

        const limitData = await getLimitForCategory(selectedCategory);

        limitInfo.style.display = 'block';

        if (limitData && limitData.has_limit && parseFloat(limitData.limit) > 0) {
          limitText.innerHTML = `<strong>Miesięczny limit dla "${selectedCategory}":</strong> ${limitData.limit} zł`;
        } else {
          limitText.innerHTML = 'Brak limitu dla wybranej kategorii';
        }
      }

      categorySelect.addEventListener('change', updateLimitInfo);
      updateLimitInfo();
    });
  </script>
</head>