"use strict";



let optionsForLineChart = {
    series: [{
        name: 'Attained',
        data: [1, 4, 3, 6, 6, 7, 8, 9, 10]
    },
        {
            name: "Break",
            data: [10, 20, 44, 6, 49, 68, 69, 91, 148]
        },
    ],
    chart: {
        height: 350,
        type: 'line',
        zoom: {
            enabled: false
        },
    },
    dataLabels: {
        enabled: false
    },
    stroke: {
        curve: 'straight'
    },
    grid: {
        row: {
            colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
            opacity: 0.5
        },
    },
    xaxis: {
        categories: [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25,
            26, 27, 28, 29, 30
        ],
    },
    fill: {
        opacity: 1
    },
    tooltip: {
        y: {
            formatter: function (val) {
                return val + " days"
            }
        }
    }
};


//

var lineChart = new ApexCharts(document.querySelector("#employeeActivityChart1"), optionsForLineChart);
lineChart.render();

var optionsforPieChart = {
    series: [7, 5, 2],
    chart: {
        width: 380,
        type: 'pie',
    },
    labels: ['Attained', 'Leave', 'Early Leave'],
    legend: {
        position: 'bottom',
    }
};

let chartPieChart = new ApexCharts(document.querySelector("#employeeActivityChart2"), optionsforPieChart);
chartPieChart.render();
