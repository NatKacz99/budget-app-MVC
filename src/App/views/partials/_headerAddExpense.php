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
      const priceInput = document.querySelector('input[name="price"]');

      let currentLimitData = null;
      let currentUsedAmount = 0;

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

          currentUsedAmount = data.sum || 0;

          if (data.sum > 0) {
            usedAmountElement.innerHTML = `<strong>${data.formatted}</strong>`;
          } else {
            usedAmountElement.innerHTML = 'Brak wydatków w tym miesiącu dla tej kategorii';
          }
        } catch (error) {
          console.error('Błąd:', error);
          usedAmountElement.innerHTML = 'Błąd pobierania danych';
          currentUsedAmount = 0;
        }
      }

      async function updateLimitBalance(additionalAmount = 0) {
        const selectedCategory = categorySelect.value;
        const selectedDate = dateInput.value;

        if (!selectedCategory || selectedCategory === 'Wybierz kategorię wydatku') {
          limitBalanceElement.innerHTML = 'Wybierz kategorię, by zobaczyć bilans';
          return;
        }

        try {
          if (!currentLimitData) {
            const response = await fetch(`/api/limit-balance?category=${selectedCategory}&date=${selectedDate}`);
            const data = await response.json();
            currentLimitData = data;
          }

          if (currentLimitData.has_limit === false) {
            limitBalanceElement.innerHTML = '<span>Brak ustawionego limitu</span>';
          } else if (currentLimitData.has_limit === true) {
            const totalUsed = currentUsedAmount + additionalAmount;
            const balance = currentLimitData.limit - totalUsed;

            const balanceClass = balance >= 0 ? 'color: rgb(17, 55, 17)' : 'color: red';
            limitBalanceElement.innerHTML = `<span style="${balanceClass}"><strong>${balance.toFixed(2)} zł</strong></span>`;
          } else {
            limitBalanceElement.innerHTML = currentLimitData.message || 'Błąd pobierania bilansu';
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

          currentLimitData = null;
          currentUsedAmount = 0;
          return;
        }

        const limitData = await getLimitForCategory(selectedCategory);
        limitInfo.style.display = 'block';

        if (limitData && limitData.has_limit && parseFloat(limitData.limit) > 0) {
          limitText.innerHTML = `Miesięczny limit dla "${selectedCategory}": <strong>${limitData.limit} zł</strong>`;
        } else {
          limitText.innerHTML = 'Brak limitu dla wybranej kategorii';
        }

        currentLimitData = null;
        currentUsedAmount = 0;

        await updateUsedAmount();
        await updateLimitBalance();
      }

      function parseInputAmount() {
        const value = priceInput.value.trim();
        if (!value) return 0;

        const cleanValue = value.replace(',', '.');
        const parsed = parseFloat(cleanValue);

        return isNaN(parsed) ? 0 : parsed;
      }

      categorySelect.addEventListener('change', updateLimitInfo);

      dateInput.addEventListener('change', async function() {
        currentLimitData = null;
        await updateUsedAmount();
        await updateLimitBalance(parseInputAmount());
      });

      priceInput.addEventListener('input', function() {
        const currentAmount = parseInputAmount();
        updateLimitBalance(currentAmount);
      });

      $('.datepicker').on('change', function() {
        currentLimitData = null;
        updateUsedAmount().then(() => {
          updateLimitBalance(parseInputAmount());
        });
      });

      updateLimitInfo();
    });
  </script>
</head>