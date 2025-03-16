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
    </div>
  </article>
  <script src="https://cdn.canvasjs.com/canvasjs.min.js"></script>
</body>

</html>