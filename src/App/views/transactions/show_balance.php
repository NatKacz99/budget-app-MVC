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
          <!-- Search Form -->
          <form method="GET" class="mt-4 w-full">
            <div class="flex">
              <input value="<?php echo e((string) $searchTerm); ?>" name="s" type="text" class="w-full rounded-l-md border-0 px-3.5 py-2 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6" style="width: 60%" placeholder=" Enter search term" />
              <button type="submit" class="rounded-r-md bg-indigo-600 px-3.5 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600" style="border-radius: 10px;">
                Search
              </button>
            </div>
          </form>
          <div class="balance">
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
                    <?php if (empty($incomes)) : ?>
                      <tr>
                        <td colspan="3">Brak wyników</td>
                      </tr>
                    <?php else : ?>
                      <?php foreach ($incomes as $income) : ?>
                        <tr>
                          <td><?php echo e($income['name']); ?></td>
                          <td><?php echo e($income['amount']); ?></td>
                          <td><?php echo e($income['formatted_date']); ?></td>
                        </tr>
                      <?php endforeach; ?>
                    <?php endif; ?>
                  </tbody>

                </table>
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
                    <?php if (empty($expenses)) : ?>
                      <tr>
                        <td colspan="3">Brak wyników</td>
                      </tr>
                    <?php else : ?>
                      <?php foreach ($expenses as $expense) : ?>
                        <tr>
                          <td><?php echo e($expense['name']) ?></td>
                          <td><?php echo e($expense['amount']) ?></td>
                          <td><?php echo e($expense['formatted_date']) ?></td>
                        </tr>
                      <?php endforeach; ?>
                    <?php endif; ?>
                  </tbody>
                </table>
              </div>
            </div>
            <div id="pie-chart-incomes-container" style="height: 300px; width: 60%;"></div>
            <div id="pie-chart-expenses-container" style="height: 300px; width: 60%;"></div>

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
</body>

</html>