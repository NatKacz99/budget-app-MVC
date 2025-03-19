<!DOCTYPE html>

<html lang="pl">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title>Przeglądaj bilans</title>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
  <link href="./css/bootstrap.min.css" rel="stylesheet">
  <script src="./js/bootstrap.min.js"></script>

  <link rel="stylesheet" href="./assets/style_show_balance.css">
  <link rel="stylesheet" href="./css/cap.css">


  <script>
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
      const resultsIncomes = <?php echo json_encode($results_incomes); ?>;
      const resultsExpenses = <?php echo json_encode($results_expenses); ?>;
      const chartExpensesDiv = document.getElementById("pie-chart-expenses-container");
      const chartIncomesDiv = document.getElementById("pie-chart-incomes-container");
      const divTables = document.getElementsByClassName("tables-incomes-expenses")[0];
      const calculationDiv = document.getElementById("calculation");
      if ((!resultsIncomes || resultsIncomes.length === 0) && (!resultsExpenses || resultsExpenses.length === 0)) {
        calculationDiv.style.display = "none";
        chartExpensesDiv.style.display = "none";
        chartIncomesDiv.style.display = "none";
        divTables.style.marginBottom = "30px";
      } else if (!resultsIncomes || resultsIncomes.length === 0) {
        chartIncomesDiv.style.display = "none";
      } else if (!resultsExpenses || resultsExpenses.length === 0) {
        chartExpensesDiv.style.display = "none";
      }
    });

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

    function handleTimeSlotChange(select) {
      if (select.value !== "niestandardowy") {
        select.form.submit();
      } else {
        $('#myModal').modal('show');
      }

    }
  </script>

</head>