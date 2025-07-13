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
      const dateInput = document.querySelector('input[name="date"]');
      const usedAmountElement = document.getElementById('usedAmountText');
      const limitBalanceElement = document.getElementById('limitBalance');

      console.log('dateInput element:', dateInput);
      console.log('dateInput initial value:', dateInput ? dateInput.value : 'NULL');

      async function updateUsedAmount() {
        const selectedCategory = categorySelect.value;
        const selectedDate = dateInput.value;


        if (!selectedCategory || selectedCategory === 'Wybierz kategorię wydatku') {
          usedAmountElement.innerHTML = 'Wybierz kategorię aby zobaczyć wykorzystaną kwotę';
          return;
        }

        try {
          const response = await fetch(`/api/monthly-expenses?category=${selectedCategory}&date=${selectedDate}`);
          const data = await response.json();

          console.log('API Response:', data);

          if (data.sum > 0) {
            usedAmountElement.innerHTML = `<strong>${data.formatted}</strong>`;
          } else {
            usedAmountElement.innerHTML = 'Brak wydatków w tym miesiącu dla tej kategorii';
          }
        } catch (error) {
          console.error('Błąd:', error);
          usedAmountElement.innerHTML = 'Błąd pobierania danych';
        }
      }

      async function updateLimitBalance() {
        const selectedCategory = categorySelect.value;
        const selectedDate = dateInput.value;

        if (!selectedCategory || selectedCategory === 'Wybierz kategorię wydatku') {
          limitBalanceElement.innerHTML = 'Wybierz kategorię, by zobaczyć bilans';
          return;
        }

        try {
          const response = await fetch(`/api/limit-balance?category=${selectedCategory}&date=${selectedDate}`);
          const data = await response.json();

          if (data.has_limit === false) {
            limitBalanceElement.innerHTML = '<span>Brak ustawionego limitu</span>';
          } else if (data.has_limit === true && data.balance) {
            const balanceValue = data.balance_raw || 0;
            const balanceClass = balanceValue >= 0 ? 'color: rgb(17, 55, 17)' : 'color: red';
            limitBalanceElement.innerHTML = `<span style="${balanceClass}"><strong>${data.balance}</strong></span>`;
          } else {
            limitBalanceElement.innerHTML = data.message || 'Błąd pobierania bilansu';
          }
        } catch (error) {
          console.error('Błąd:', error);
          limitBalanceElement.innerHTML = 'Błąd pobierania bilansu';
        }
      }

      async function updateLimitInfo() {
        const selectedCategory = categorySelect.value;

        if (!selectedCategory || selectedCategory === '' || selectedCategory === 'Wybierz kategorię wydatku') {
          limitInfo.style.display = 'block';
          limitText.innerHTML = 'Wymagany wybór kategorii';
          usedAmountElement.innerHTML = 'Wybierz kategorię aby zobaczyć wykorzystaną kwotę';
          limitBalanceElement.innerHTML = 'Wybierz kategorię, by zobaczyć bilans';
          return;
        }

        const limitData = await getLimitForCategory(selectedCategory);
        limitInfo.style.display = 'block';

        if (limitData && limitData.has_limit && parseFloat(limitData.limit) > 0) {
          limitText.innerHTML = `Miesięczny limit dla "${selectedCategory}": <strong>${limitData.limit} zł</strong>`;
        } else {
          limitText.innerHTML = 'Brak limitu dla wybranej kategorii';
        }

        await updateUsedAmount();
        await updateLimitBalance();
      }

      categorySelect.addEventListener('change', updateLimitInfo);
      dateInput.addEventListener('change', async function() {
        await updateUsedAmount();
        await updateLimitBalance();
      });

      $('.datepicker').on('change', function() {
        updateUsedAmount();
        updateLimitBalance();
      });

      updateLimitInfo();
    });
  </script>
</head>