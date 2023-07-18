"use strict";

// revenue chart

var revenueChartoptions = {
  series: [
    {
      name: "Earning",
      data: [44005, 55888, 57555, 56238, 61455, 58346, 63000, 62209, 66342, 454242, 67424],
    },
    {
      name: "Expense",
      data: [76245, 85346, 10186, 98222, 87424, 10558, 91535, 11453, 94536, 34354, 56222],
    }
  ],

  colors : ['#6CD6FD', '#DEF6FF'],
  chart: {
    type: "bar",
    height: 350,
    toolbar: {
      show: false,
    }
  },
  plotOptions: {
    bar: {
    horizontal: false,
      columnWidth: "55%",
      endingShape: "rounded",
    },
  },

  legend: {
    itemMargin: {
      horizontal: 5,
      vertical: 20,
    },
    horizontalAlign: "center",
    verticalAlign: "center",
    position: "bottom",
    fontSize: "14px",
    fontWight: "bold",
    markers: {
      radius: 5,
      height: 14,
      width: 14,
    },
  },

  dataLabels: {
    enabled: false,

  },
  stroke: {
    show: true,
    width: 2,
    colors: ["transparent"],
  },
  xaxis: {
    categories: ["Jan", "Feb", "Mar", "Apr", "May", "June", "July", "Aug", "Sep", "Oct", 'Nov'],
  },
  yaxis: {
    title: {
      show: false,
    },
    labels: {
      formatter: function(value) {
        var val = Math.abs(value)
        if (val >= 1000) {
          val = (val / 1000).toFixed(0) + 'K'
        }
        return val
      }
    }
  },
  fill: {
    opacity: 1,
  },
  tooltip: {
    y: {
      formatter: function (val) {
        return val + "K";
      },
    },
  },
};

console.log(revenueChartoptions);

if (document.querySelector("#revenueChart")) {
  var revenueChart = new ApexCharts(document.querySelector("#revenueChart"), revenueChartoptions);
  revenueChart.render();
}else{
  console.log('revenueChart not found');
}


let otPieChart1 = {
  series: [623, 314, 239],
  chart: {
    id: "ot-pie-chart-alpha",
    fontFamily: "Lexend, sans-serif",
    type: "donut",
    height: 360,
    toolbar: {
      show: false,
    },
    zoom: {
      enabled: false,
    },
  },

  colors: ["#5669FF", "#55B1F3", "#FF4F75"],

  labels: ["Check In", "Check Out", "Absence"],

  dataLabels: {
    enabled: false,
  },
  plotOptions: {
    pie: {
      donut: {
        size: "85%",
        labels: {
          show: true,
          name: {
            fontSize: "30px",
            offsetY: 0,
            color: "#636E72",
          },
          value: {
            fontSize: "30px",
            width: 700,
            offsetY: 20,

            formatter(val) {
              return `${val}`;
            },
          },
          total: {
            show: true,
            fontSize: "18px",
            color: "#636E72",
            label: "Total",

            formatter: function (w) {
              return "2400";
            },
          },
        },
      },
    },
  },

  legend: {
    itemMargin: {
      horizontal: 5,
      vertical: 20,
    },
    horizontalAlign: "center",
    verticalAlign: "center",
    position: "top",
    fontSize: "16px",
    fontWight: "bold",
    markers: {
      radius: 3,
      height: 15,
      width: 15,
    },
  },

  responsive: [
    {
      breakpoint: 325,
      options: {
        legend: {
          itemMargin: {
            horizontal: 4,
            vertical: 0,
          },
          horizontalAlign: "center",
          position: "bottom",
          fontSize: "14px",
        },
      },
    },
  ],
};

if (document.querySelector("#ot-pie-chart-alpha")) {
  let chart = new ApexCharts(
    document.querySelector("#ot-pie-chart-alpha"),
    otPieChart1
  );
  chart.render();
}
 
