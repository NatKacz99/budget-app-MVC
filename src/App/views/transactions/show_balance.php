<?php include $this->resolve("partials/_headerBalance.php"); ?>

<body>
  <?php include $this->resolve("partials/_navigation.php"); ?>
  <article>
    <div class="container-outside">
      <div class="container-inside">
        <section>
          <form id="form_balance" method="post">
            <?php include $this->resolve('partials/_csrf.php') ?>
            <div class="check-period">
              <div>
                <label for="time-slot">
                  <select id="time-slot" name="time-slot" onchange="handleTimeSlotChange(this)">
                    <option selected disabled>Wybierz okres czasu</option>
                    <option value="bieżący_miesiąc" <?php echo $selected_period === 'bieżący_miesiąc' ? 'selected' : ''; ?>>bieżący miesiąc</option>
                    <option value="poprzedni_miesiąc" <?php echo $selected_period === 'poprzedni_miesiąc' ? 'selected' : ''; ?>>poprzedni miesiąc</option>
                    <option value="bieżący_rok" <?php echo $selected_period === 'bieżący_rok' ? 'selected' : ''; ?>>bieżący rok</option>
                    <option value="niestandardowy" <?php echo $selected_period === 'niestandardowy' ? 'selected' : ''; ?>>niestandardowy</option>
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
                        <input type="text" name="start_day" class="datepicker form-control" value="<?php echo $current_day ?>">
                      </div>
                      do:
                      <div class="input-group">
                        <span class="icon-container"><i class="icon-calendar"></i></span>
                        <input type="text" name="end_day" class="datepicker form-control" value="<?php echo $current_day ?>">
                      </div>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-danger" data-dismiss="modal">Zamknij</button>
                      <button type="submit" class="btn btn-success">Zapisz zmiany</button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </form>
        </section>

        <section>

          <div class="balance">
            <!-- Search Form -->
            <div>
              <form method="GET" class="mt-4 w-full">
                <div class="flex">
                  <input value="<?php echo e((string) $searchTerm); ?>" name="s" type="text" class="w-full rounded-l-md border-0 px-3.5 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" placeholder="Enter search term" />
                  <button type="submit" class="rounded-r-md bg-indigo-600 px-3.5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">
                    Search
                  </button>
                </div>
              </form>
            </div>
            <div class="tables-incomes-expenses">
              <div>
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
                        <td><?php echo e($income['name']) ?></td>
                        <td><?php echo e($income['amount']) ?></td>
                        <td><?php echo e($income['formatted_date']) ?></td>
                      </tr>
                    <?php endforeach; ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>

          <div>
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
                    <td><?php echo e($expense['name']) ?></td>
                    <td><?php echo e($expense['amount']) ?></td>
                    <td><?php echo e($expense['formatted_date']) ?></td>
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>
          </div>
      </div>
      <div id="pie-chart-incomes-container" style="height: 300px; width: 60%;"></div>
      <div id="pie-chart-expenses-container" style="height: 300px; width: 60%;"></div>

      <div id="calculation">
        <?php
        $balance = $total_sum_incomes - $total_sum_expenses;
        $balance_sheet = $balance . " zł";
        ?>
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

    </div>
    </section>
    </div>
    <!-- Previous Page Link -->
    <div class="-mt-px flex w-0 flex-1">
      <?php if ($currentPage > 1) : ?>
        <a href="/?<?php echo e($previousPageQuery); ?>" class="inline-flex items-center border-t-2 border-transparent pr-1 pt-4 text-sm font-medium text-gray-500 hover:border-gray-300 hover:text-gray-700">
          <svg class="mr-3 h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
            <path fill-rule="evenodd" d="M18 10a.75.75 0 01-.75.75H4.66l2.1 1.95a.75.75 0 11-1.02 1.1l-3.5-3.25a.75.75 0 010-1.1l3.5-3.25a.75.75 0 111.02 1.1l-2.1 1.95h12.59A.75.75 0 0118 10z" clip-rule="evenodd" />
          </svg>
          Previous
        </a>
      <?php endif; ?>
    </div>
    </div>
  </article>
  <script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>
</body>

</html>