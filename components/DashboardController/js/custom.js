const module = `${URL_BASED}component/dashboard/`;
const component = `component/dashboard/`;

$(document).ready(function () {
  predictiveAnalytic();

  totalClientsPerYear();

  topseelingProducts();

  userIDentity();
  dailysales();
  $(window).on("resize", function () {
    window.predictiveAnalytic.redraw();
    window.totalClientsPerYear.redraw();
    window.userIDentity.redraw();
    window.dailysales.redraw();
  });
});

function dailysales() {
  var action = module + "dailysales";
  var formData = new FormData();

  var request = main.send_ajax(formData, action, "POST", true);
  request.done(function (data) {
    window.lineChart = Morris.Line({
      element: "dailysales",
      data: data,
      xkey: "y",
      redraw: true,
      ykeys: ["actual"],
      labels: ["Daily Sales"],
      lineColors: ["#1E90FF"],
      parseTime: false,
      hideHover: "auto",
      resize: true,
    });
  });
}

function predictiveAnalytic() {
  var action = module + "predictiveAnalytic";
  var formData = new FormData();

  var request = main.send_ajax(formData, action, "POST", true);
  request.done(function (data) {
    console.log(data);
    // Add predictions after last actual year
    let last = data[data.length - 1];
    let year = parseInt(last.y);
    let value = last.actual;
    let start = data[0].actual;
    let startYear = parseInt(data[0].y);
    let periods = year - startYear;

    let cagr = Math.pow(last.actual / start, 1 / periods) - 1;
    console.log("CAGR:", cagr);

    for (let i = 1; i <= 10; i++) {
      year += 1;
      value = Math.round(value * (1 + cagr));
      data.push({
        y: year.toString(),
        actual: null,
        predictive: value,
      });
    }

    window.lineChart = Morris.Line({
      element: "prediictve",
      data: data,
      xkey: "y",
      redraw: true,
      ykeys: ["actual", "predictive"],
      labels: ["Actual Sales", "Predictive Sales"],
      lineColors: ["#1E90FF", "#FF9F55"],
      parseTime: false,
      hideHover: "auto",
      resize: true,
    });
  });
}

function totalClientsPerYear() {
  // Total Clients chart

  var action = module + "totalClientsPerYear";
  var formData = new FormData();

  var request = main.send_ajax(formData, action, "POST", true);
  request.done(function (data) {
    window.totalClientsPerYear = Morris.Area({
      element: "morris-extra-area-totalclients",
      data: data,
      lineColors: ["#7E81CB"],
      xkey: "y",
      ykeys: ["clients"],
      labels: ["Total Clients"],
      pointSize: 0,
      lineWidth: 0,
      resize: true,
      fillOpacity: 0.8,
      behaveLikeLine: true,
      gridLineColor: "#5FBEAA",
      hideHover: "auto",
      parseTime: false,
    });
  });
}

function topseelingProducts() {
  var action = module + "topseelingProducts";
  var formData = new FormData();

  var request = main.send_ajax(formData, action, "POST", true);
  request.done(function (data) {
    var options = {
      series: [
        {
          data: data.total,
        },
      ],
      chart: {
        height: 350,
        type: "bar",
      },
      plotOptions: {
        bar: {
          columnWidth: "45%",
          distributed: true,
        },
      },
      dataLabels: {
        enabled: false,
      },
      legend: {
        show: false,
      },
      xaxis: {
        categories: data.label,
      },
    };

    var chart = new ApexCharts(
      document.querySelector("#morris-bar-chart-topseling"),
      options
    );
    chart.render();
  });
}

/*Donut chart*/
function userIDentity() {
  var action = module + "userIDentity";
  var formData = new FormData();

  var request = main.send_ajax(formData, action, "POST", true);
  request.done(function (data) {
    window.areaChart = Morris.Donut({
      element: "donut-example-user-identity",
      redraw: true,
      data: data,
      colors: ["#5FBEAA", "#FF9F55"],
    });
  });
}
