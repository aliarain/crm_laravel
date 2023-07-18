$(document).ready(function () {
    // base url from meta tag
    var baseUrl = $('meta[name="base-url"]').attr('content');
    //tokens from base meta tag
    var _token = $('meta[name="csrf-token"]').attr('content');


    let optionsForLineChart = {
        series: [{
            name: 'Attained',
            data: [1, 4, 3, 6, 6, 7, 8, 9, 10]
        },
            {
                name: "Break",
                data: [10, 20, 44, 6, 49, 68, 69, 91, 148]
            },
            {
                name: "Test",
                data: [1, 35, 75, 62, 49, 90, 69, 45, 148]
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


        //call dynamic data
        $.ajax({
            type: "GET",
            dataType: "json",
            // url: baseUrl + '/' + 'dashboard/current-month-pie-chart',
            success: function (data) {
                series = [3, 4, 5, 6] //default data
                labels = data.data.labels
                drawPieChart(series, labels)
            }
        });

        function drawPieChart(series, labels) {
            let optionsforPieChart = {
                series: series,
                chart: {
                    width: 380,
                    type: 'pie',
                },
                labels: labels,
                legend: {
                    position: 'bottom',
                }
            };
            let chartPieChart = new ApexCharts(document.querySelector("#employeeActivityChart2"), optionsforPieChart);
            chartPieChart.render();
        }


});
