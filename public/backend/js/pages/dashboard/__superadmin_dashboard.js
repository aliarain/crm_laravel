"use strict"

 // Bar Chart
 var optionBar = {
    series: [{
        data: [40, 50, 60, 30, 70]
    }],
    chart: {
        toolbar: {
            show: false,
        },
        type: "bar",
        height: 150
    },
    plotOptions: {
        bar: {
            horizontal: false,
            columnWidth: '20%',
            distributed: true,
            startingShape: "rounded",
            endingShape: "rounded",
            colors: {
                backgroundBarColors: ["#eee"],
                backgroundBarOpacity: 1,
                backgroundBarRadius: 7
            }
        }
    },
    dataLabels: {
        enabled: false
    },
    legend: {
        show: false,
    },
    grid: {
        yaxis: {
            lines: {
                show: false
            }
        }
    },
    xaxis: {
        show: false,
        labels: {
            show: false,
        },
        axisBorder: {
            show: false
        },
        axisTicks: {
            show: false,
        },

    },
    yaxis: {
        show: false,
        labels: {
            show: false,
        },
        axisBorder: {
            show: false,
        },
        axisTicks: {
            show: false,
        },
        crosshairs: {
            show: false,
        },
        tooltip: {
            enabled: false,
        },

    },
    colors: [

        "#fad388"

    ],

};
var chart = new ApexCharts(document.querySelector("#barChart"), optionBar);
chart.render();
// end
// Line Chart
var optionBar = {
    series: [{
        data: [40, 50, 60, 30, 70]
    }],
    stroke: {
        width: 4
    },
    chart: {
        toolbar: {
            show: false,
        },
        type: "line",
        height: 150
    },

    dataLabels: {
        enabled: false
    },
    legend: {
        show: false,
    },

    grid: {
        yaxis: {
            lines: {
                show: false
            }
        }
    },
    xaxis: {
        show: false,
        labels: {
            show: false,
        },
        axisBorder: {
            show: false
        },
        axisTicks: {
            show: false,
        },

    },
    yaxis: {
        show: false,
        labels: {
            show: false,
        },
        axisBorder: {
            show: false,
        },
        axisTicks: {
            show: false,
        },
        crosshairs: {
            show: false,
        },
        tooltip: {
            enabled: false,
        },

    },
    colors: [

        "#4CD6EB"

    ],
    markers: {
        size: 5
    }

};
var chart = new ApexCharts(document.querySelector("#lineChart"), optionBar);
chart.render();
// end

var option = {
    series: [{

        data: [44, 55, 57, 56, 61, 58, 63, 60, 66]
    }],
    chart: {
        toolbar: {
            show: false,
        },
        type: "bar",
        height: 350
    },
    plotOptions: {
        bar: {
            horizontal: false,
            columnWidth: '20%',
            distributed: true,
            startingShape: "rounded",
            endingShape: "rounded",
        }
    },
    dataLabels: {
        enabled: false
    },
    legend: {
        show: false,
        position: 'top'
    },
    grid: {
        yaxis: {
            lines: {
                show: false
            }
        }
    },
    xaxis: {
        categories: ['Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct'],
        labels: {
            show: true,
        },
        axisBorder: {
            show: false
        },
        axisTicks: {
            show: false,
        },
    },
    yaxis: {
        show: true,
        labels: {
            show: true,
        },
        axisBorder: {
            show: false,
        },
        axisTicks: {
            show: false,
        },
        crosshairs: {
            show: false,
        },
        tooltip: {
            enabled: false,
        },

    },
    colors: [

        "#fad388"

    ],

};
var chart = new ApexCharts(document.querySelector("#revenueChart"), option);
chart.render();

// start
var optionBar = {
    series: [{
            data: [45, 52, 38, 24, 33, 26, 21, 20, 6, 8, 15, 10]
        },
        {
            data: [35, 41, 62, 42, 13, 18, 29, 37, 36, 51, 32, 35]
        }
    ],
    stroke: {
        width: 4
    },
    chart: {
        toolbar: {
            show: false,
        },
        type: "line",
        height: 150
    },

    dataLabels: {
        enabled: false
    },
    legend: {
        show: false,
    },

    grid: {
        yaxis: {
            lines: {
                show: false
            }
        }
    },
    xaxis: {
        show: false,
        labels: {
            show: false,
        },
        axisBorder: {
            show: false
        },
        axisTicks: {
            show: false,
        },

    },
    yaxis: {
        show: false,
        labels: {
            show: false,
        },
        axisBorder: {
            show: false,
        },
        axisTicks: {
            show: false,
        },
        crosshairs: {
            show: false,
        },
        tooltip: {
            enabled: false,
        },

    }

};
var chart = new ApexCharts(document.querySelector("#dashDotted"), optionBar);
chart.render();
// end
// end
// start
var options = {
    series: [44, 55, 41, 60],
    labels: ["Transport", "Shopping", "Energy use", "Food"],
    dataLabels: {
        enabled: false
    },
    chart: {
        type: 'donut',
        height: 150
    },
    plotOptions: {
        pie: {
            donut: {
                labels: {
                    show: true,
                    total: {
                        show: true,
                        fontSize: '12px',
                        color: '#000',
                        formatter: () => '55'
                    }
                },
                value: {
                    show: true,
                    fontSize: '10px',
                    fontFamily: 'Helvetica, Arial, sans-serif',
                    fontWeight: 400,
                    color: undefined,
                    offsetY: 16,
                    formatter: function(val) {
                        return val
                    }
                },
            }
        }
    },
    tooltip: {
        style: {
            fontSize: '10px',
            fontFamily: undefined
        }
    },
    legend: {
        show: false,
    },
    fill: {
        colors: ['#28c76f', '#28c76f66', '#28c76f33']
    }
};

var chart = new ApexCharts(document.querySelector("#pieChart"), options);
chart.render();
// end
