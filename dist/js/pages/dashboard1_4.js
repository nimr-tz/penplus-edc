/*
 * Author: Abdullah A Almsaeed
 * Date: 4 Jan 2014
 * Description:
 *      This is a demo file used only for the main dashboard (index.html)
 **/

/* global moment:false, Chart:false, Sparkline:false */

$(function () {
  ("use strict");

  // Make the dashboard widgets sortable Using jquery UI
  $(".connectedSortable").sortable({
    placeholder: "sort-highlight",
    connectWith: ".connectedSortable",
    handle: ".card-header, .nav-tabs",
    forcePlaceholderSize: true,
    zIndex: 999999,
  });
  $(".connectedSortable .card-header").css("cursor", "move");

  // bootstrap WYSIHTML5 - text editor
  $(".textarea").summernote();

  $(".daterange").daterangepicker(
    {
      ranges: {
        Today: [moment(), moment()],
        Yesterday: [moment().subtract(1, "days"), moment().subtract(1, "days")],
        "Last 7 Days": [moment().subtract(6, "days"), moment()],
        "Last 30 Days": [moment().subtract(29, "days"), moment()],
        "This Month": [moment().startOf("month"), moment().endOf("month")],
        "Last Month": [
          moment().subtract(1, "month").startOf("month"),
          moment().subtract(1, "month").endOf("month"),
        ],
      },
      startDate: moment().subtract(29, "days"),
      endDate: moment(),
    },
    function (start, end) {
      // eslint-disable-next-line no-alert
      alert(
        "You chose: " +
          start.format("MMMM D, YYYY") +
          " - " +
          end.format("MMMM D, YYYY")
      );
    }
  );

  // SETUP BLOCK

  fetch("process4.php")
    .then((response) => response.json())
    .then((data) => {
      const monthname = Object.keys(data);
      const amana = monthname.map((monthname) => data[monthname]["1"]);
      const mwananyamala = monthname.map((monthname) => data[monthname]["2"]);
      const temeke = monthname.map((monthname) => data[monthname]["3"]);
      const mbagala = monthname.map((monthname) => data[monthname]["4"]);
      const magomeni = monthname.map((monthname) => data[monthname]["5"]);


        var ticksStyle = {
          fontColor: "#495057",
          fontStyle: "bold",
        };

      /* Chart.js Charts */
      // registration
      var salesChartCanvas = document
        .getElementById("enrolled")
        .getContext("2d");
      // $('#revenue-chart').get(0).getContext('2d');

      // var ctx = document.getElementById("registration").getContext("2d");

      var salesChartData = {
        labels: monthname,
        datasets: [
          {
            label: "KONDOA HOSPITAL",
            // backgroundColor: "rgba(60,141,188,0.9)",
            backgroundColor: "pink",
            borderColor: "rgba(60,141,188,0.8)",
            pointRadius: false,
            pointColor: "#3b8bba",
            pointStrokeColor: "rgba(60,141,188,1)",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(60,141,188,1)",
            data: amana,
          },
          {
            label: "KARATU HOSPITAL",
            // backgroundColor: "rgba(210, 214, 222, 1)",
            backgroundColor: "blue",
            borderColor: "rgba(210, 214, 222, 1)",
            pointRadius: false,
            pointColor: "rgba(210, 214, 222, 1)",
            pointStrokeColor: "#c1c7d1",
            pointHighlightFill: "#fff",
            pointHighlightStroke: "rgba(220,220,220,1)",
            data: mwananyamala,
          },
        ],
      };

      var salesChartOptions = {
        maintainAspectRatio: false,
        responsive: true,
        legend: {
          display: false,
        },
        scales: {
          xAxes: [
            {
              gridLines: {
                display: false,
              },
            },
          ],
          yAxes: [
            {
              gridLines: {
                display: false,
              },
              ticks: $.extend(
                {
                  beginAtZero: true,
                  stepSize: 1,
                  suggestedMax: 10,
                },
                ticksStyle
              ),
            },
          ],
        },
      };

      // This will get the first returned node in the jQuery collection.
      // eslint-disable-next-line no-unused-vars
      var salesChart = new Chart(salesChartCanvas, {
        // lgtm[js/unused-local-variable]
        type: "bar",
        data: salesChartData,
        options: salesChartOptions,
      });
    });
});
