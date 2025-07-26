<?php include $this->resolve("partials/_headerBalance.php"); ?>

<body>
  <?php include $this->resolve("partials/_navigation.php"); ?>
  <article>
    <div class="container-outside">
      <div class="container-inside">
        <section>
          <?php
          $selected_period = $_SESSION['selected_period'] ?? '';
          $page = $_GET['pageNum'] ?? 1;
          ?>
          <form id="form_balance" method="get" action="balance">
            <input type="hidden" name="pageNum" value="<?= e($page); ?>">

            <?php include $this->resolve('partials/_csrf.php') ?>
            <div class="check-period">
              <label for="time-slot">
                <select id="time-slot" name="time-slot" onchange="handleTimeSlotChange(this, event)">
                  <option disabled>Wybierz okres czasu</option>
                  <option value="bieżący_miesiąc">Bieżący miesiąc</option>
                  <option value="poprzedni_miesiąc" <?= $selected_period === 'poprzedni_miesiąc' ? 'selected' : ''; ?>>Poprzedni miesiąc</option>
                  <option value="bieżący_rok" <?= $selected_period === 'bieżący_rok' ? 'selected' : ''; ?>>Bieżący rok</option>
                  <option value="niestandardowy" <?= $selected_period === 'niestandardowy' ? 'selected' : ''; ?>>Niestandardowy</option>
                </select>
              </label>
            </div>
            <?php $current_day = date('Y-m-d'); ?>
            <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
              <div class="modal-dialog" role="document">
                <div class="modal-content">
                  <div class="modal-body">
                    Zakres od:
                    <div class="input-group">
                      <span class="icon-container"><i class="icon-calendar"></i></span>
                      <input type="text" name="startDay" class="datepicker form-control" value="<?php echo isset($startDay) ? $startDay : $current_day; ?>">
                    </div>
                    do:
                    <div class="input-group">
                      <span class="icon-container"><i class="icon-calendar"></i></span>
                      <input type="text" name="endDay" class="datepicker form-control" value="<?php echo isset($endDay) ? $endDay : $current_day ?>">
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Zamknij</button>
                    <button type="submit" class="btn btn-success">Zapisz zmiany</button>
                  </div>
                </div>
          </form>
        </section>

        <section>
          <!-- Search Form -->
          <form method="GET" class="mt-4 w-full">
            <div class="flex">
              <input value="<?php echo e((string) $searchTerm); ?>" name="searchTerm" type="text" class="w-full rounded-l-md border-0 px-3.5 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" style="width: 60%" placeholder="Wprowadź szukaną frazę kategorii wydatku lub przychodu" />
              <button type="submit" class="rounded-r-md bg-indigo-600 px-3.5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600" style="border-radius: 10px;">
                Search
              </button>
            </div>
          </form>
          <div class="balance">
            <div class="tables-incomes-expenses">
              <div class="incomes">
                <h3>Przychody</h3>
                <table>
                  <thead>
                    <tr>
                      <th class="header-category">Kategoria</th>
                      <th class="header-amount">Kwota (zł)</th>
                      <th class="header-date">Data</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($incomes as $income) : ?>
                      <tr>
                        <td><?php echo e($income['name']); ?></td>
                        <td><?php echo e($income['amount']); ?></td>
                        <td><?php echo e($income['formatted_date']); ?></td>
                      </tr>
                    <?php endforeach; ?>

                  </tbody>

                </table>
                <?php if ($incomesCount > 1) : ?>
                  <b>Suma całkowita </b><?php echo e($sumIncomes); ?>
                <?php endif; ?>
              </div>

              <div class="expenses">
                <h3>Wydatki</h3>
                <table>
                  <thead>
                    <tr>
                      <th class="header-category">Kategoria</th>
                      <th class="header-amount">Kwota (zł)</th>
                      <th class="header-date">Data</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($expenses as $expense) : ?>
                      <tr>
                        <td><?php echo e($expense['name']); ?></td>
                        <td><?php echo e($expense['amount']); ?></td>
                        <td><?php echo e($expense['formatted_date']); ?></td>
                      </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
                <?php if ($expensesCount > 1) : ?>
                  <b>Suma całkowita </b><?php echo e($sumExpenses); ?>
                <?php endif; ?>
              </div>
            </div>

            <div class="tables-incomes-expenses-sum-for-categories">
              <h2>Kwoty przychodów i wydatków sumarycznie dla danej kategorii</h2>
              <div class="tables-container">
                <div class="incomes">
                  <h3>Przychody</h3>
                  <table>
                    <thead>
                      <tr>
                        <th class="header-category">Kategoria</th>
                        <th class="header-amount">Kwota (zł)</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($categoriesIncomes as $categoryIncome) : ?>
                        <tr>
                          <td><?php echo e($categoryIncome['name']); ?></td>
                          <td><strong><?php echo e($categoryIncome['sumCategory']); ?></strong></td>
                        </tr>
                      <?php endforeach; ?>
                    </tbody>
                  </table>
                </div>

                <div class="expenses">
                  <h3>Wydatki</h3>
                  <table>
                    <thead>
                      <tr>
                        <th class="header-category">Kategoria</th>
                        <th class="header-amount">Kwota (zł)</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php foreach ($categoriesExpenses as $categoryExpense) : ?>
                        <tr>
                          <td><?php echo e($categoryExpense['name']); ?></td>
                          <td><strong><?php echo e($categoryExpense['sumCategory']); ?></strong></td>
                        </tr>
                      <?php endforeach; ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
            <p id="lack" style="display: none"><b>Brak wyników.</b></p>
            <div id="pie-chart-incomes-container" style="height: 300px; width: 60%;"></div>
            <div id="pie-chart-expenses-container" style="height: 300px; width: 60%;"></div>

            <div id="calculation">
              <span>
                <h3>Bilans</h3>
                <?php if ($balance < 0) { ?>
                  <h3 style="color: red"><?php echo $balance_sheet; ?></h3>
                  <div id="balance-negative-message"><?php echo "Uważaj, wpadasz w długi!"; ?></div>
                <?php } else if ($balance > 0) { ?>
                  <h3 style="color: green"><?php echo $balance_sheet; ?></h3>
                  <div id="balance-positive-message"><?php echo "Gratulacje. Świetnie zarządzasz finansami!"; ?></div>
                <?php } ?>
              </span>
            </div>

            <ul class="pagination">
              <!-- Poprzednia strona -->
              <li class="<?= ($currentPage <= 1) ? 'disabled' : '' ?>">
                <a href="<?= ($currentPage > 1) ? '?' . $previousPageQuery : '#' ?>">&laquo;</a>
              </li>

              <!-- Numery stron -->
              <?php foreach ($pageLinks as $index => $queryString): ?>
                <li class="<?= ($index + 1 == $currentPage) ? 'active' : '' ?>">
                  <a href="?<?= $queryString ?>"><?= $index + 1 ?></a>
                </li>
              <?php endforeach; ?>

              <!-- Następna strona -->
              <li class="<?= ($currentPage >= $lastPage) ? 'disabled' : '' ?>">
                <a href="<?= ($currentPage < $lastPage) ? '?' . $nextPageQuery : '#' ?>">&raquo;</a>
              </li>
            </ul>

          </div>
        </section>
      </div>
    </div>
  </article>
  <script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>
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

    function handleTimeSlotChange(select, event) {
      event.preventDefault();
      if (select.value !== "niestandardowy") {
        select.form.submit();
      } else {
        $('#myModal').modal('show');
      }
    }

    window.onload = function() {

      var chartIncomes = new CanvasJS.Chart("pie-chart-incomes-container", {
        animationEnabled: true,
        backgroundColor: "rgba(155, 224, 224, 0.5)",

        title: {
          text: "Przychody"
        },

        data: [{
          type: "pie",
          yValueFormatString: "#,##0.00\"%\"",
          indexLabel: "{label} ({y})",
          dataPoints: <?php echo json_encode($dataPointsIncomes, JSON_NUMERIC_CHECK); ?>
        }]
      });
      chartIncomes.render();


      var chartExpenses = new CanvasJS.Chart("pie-chart-expenses-container", {
        animationEnabled: true,
        backgroundColor: "rgba(155, 224, 224, 0.5)",

        title: {
          text: "Wydatki"
        },

        data: [{
          type: "pie",
          yValueFormatString: "#,##0.00\"%\"",
          indexLabel: "{label} ({y})",
          dataPoints: <?php echo json_encode($dataPointsExpenses, JSON_NUMERIC_CHECK); ?>
        }]
      });
      chartExpenses.render();

    }

    document.addEventListener("DOMContentLoaded", function() {
      const resultsIncomesCategory = <?php echo json_encode($categoriesIncomes); ?>;
      const resultsExpensesCategory = <?php echo json_encode($categoriesExpenses); ?>;
      const chartExpensesDiv = document.getElementById("pie-chart-expenses-container");
      const chartIncomesDiv = document.getElementById("pie-chart-incomes-container");
      const divTables = document.getElementsByClassName("tables-incomes-expenses")[0];
      const calculationDiv = document.getElementById("calculation");
      const resultsIncomes = <?php echo json_encode($incomes); ?>;
      const resultsExpenses = <?php echo json_encode($expenses); ?>;
      const divTableIncomes = document.getElementsByClassName("incomes")[0];
      const divTableExpenses = document.getElementsByClassName("expenses")[0];
      const lack = document.getElementById("lack");

      const summaryTables = document.getElementsByClassName("tables-incomes-expenses-sum-for-categories")[0];
      const summaryIncomesTable = summaryTables.querySelector(".incomes");
      const summaryExpensesTable = summaryTables.querySelector(".expenses");

      if ((!resultsIncomesCategory || resultsIncomesCategory.length === 0) && (!resultsExpensesCategory || resultsExpensesCategory.length === 0)) {
        chartExpensesDiv.style.display = "none";
        chartIncomesDiv.style.display = "none";
        divTables.style.display = "none";
        calculationDiv.style.display = "none";
        lack.style.display = "block";
        summaryTables.style.display = "none";
      } else {
        if (!resultsIncomesCategory || resultsIncomesCategory.length === 0) {
          chartIncomesDiv.style.display = "none";
          summaryIncomesTable.style.display = "none";
        }
        if (!resultsExpensesCategory || resultsExpensesCategory.length === 0) {
          chartExpensesDiv.style.display = "none";
          summaryExpensesTable.style.display = "none";
        }
      }

      if (!resultsIncomes || resultsIncomes.length === 0) {
        divTableIncomes.style.display = "none";
      }
      if (!resultsExpenses || resultsExpenses.length === 0) {
        divTableExpenses.style.display = "none";
      }
    });
  </script>
</body>

</html>