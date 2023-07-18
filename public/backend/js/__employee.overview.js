$(document).ready(function () {
    // base url from meta tag
    var baseUrl = $('meta[name="base-url"]').attr('content');
    //tokens from base meta tag
    var _token = $('meta[name="csrf-token"]').attr('content');

    var doughChartCtx = document.getElementById("doughChart");
    var barVarChartCtx = document.getElementById("barVarChart");
    var barHorChartCtx = document.getElementById("barHorChart");
    var lineChartCtx = document.getElementById("lineChart");
    let present_data=$('#present_data').html();
    let leave_data=$('#leave_data').html();
    let absent_data=$('#absent_data').html();


    new Chart(doughChartCtx, {
        type: 'doughnut',
        data: {
            labels: ['Leave', 'Absent', 'Present'],
            datasets: [{
                label: '# of Tomatoes',
                data: [
                    leave_data, absent_data, present_data
                ],
                backgroundColor: [
                    'rgba(68, 102, 242)',
                    'rgba(252, 87, 16, 1)',
                    'rgba(5, 189, 82, 1)',
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
        }
    });

    new Chart(barVarChartCtx, {
        type: "bar",
        data: {
            labels: ["General Admin", "Abid", "Ebu"],
            datasets: [{
                barThickness: 20,
                data: [6, 5, 10, 15, 20, 25],
                backgroundColor: [
                    'rgba(26, 71, 255, 1)',
                ],
            }]
        },
        options: {
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    new Chart(barHorChartCtx, {
        type: "bar",
        data: {
            labels: ["Promotion", "Average", "Branding", "sales"],
            datasets: [{
                barThickness: 20,
                data: [5, 14, 8, 5, 17],
                fillColor: ["rgba(220,220,220,0.5)", "navy", "red", "orange"],
                backgroundColor: [
                    'rgba(68, 102, 242, 1)',
                    'rgba(39, 174, 96, 1)',
                    'rgba(68, 102, 242, 1)',
                    'rgba(68, 102, 242, 1)',
                ],
            }, ]
        },
        options: {
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            },
            indexAxis: 'y',
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });


    //line chart for deals
    function randomNumber(min, max) {
        var number= Math.random() * (max - min) + min;
        return Math.floor(number);
      }
    const lineChartData = {};

   let expense_data= $.ajax({
        type: "GET",
        dataType: "json",
        url: baseUrl + '/' + 'dashboard/current-month-pie-chart',
        success: function (data) {
            let expense_category_div=$('.expense_category_list');
            let expense_category_list=expense_category_div.html();
            let dataArray = [];
            let cat_data = [];
            expense_category_div.empty();

            let all_categories=data.data.categories;

            all_categories.forEach(category => {

                var rgb_color=`rgb(${randomNumber(0,255)},${randomNumber(0,255)},${randomNumber(0,255)})`;
                var setData = {
                            label: category,
                            data: data.data.expenses[category],
                            borderWidth: 1,
                            tension: 0.4,
                            backgroundColor: [
                                rgb_color,
                            ],
                            borderColor: [
                                rgb_color,
                            ],
                }
                dataArray.push(setData);

                var category_html=`<div class="data-group-item" style="color: rgb(94 125 187);">
                <span class="square" style="background-color: ${rgb_color};"></span>
                <span class="title">${category}</span>
            </div>`;
                expense_category_div.append(category_html);
            });
            lineChartData.allDeals = {

                dates: [
                    ...data.data.thisMonthArray
                ],
                categories: all_categories,
                category_expenses: [data.data.expenses],
                dataArray: dataArray,

            };

            function line() {
                const data = {
                    labels: lineChartData.allDeals.dates,
                    datasets: dataArray,

                };
                const config = {
                    type: 'line',
                    data: data,
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        },
                        plugins: {
                            legend: {
                                display: false
                            },
                        }
                    }
                };

                new Chart(lineChartCtx, config);
            }

            line()
        }
    });
    // lineChartData.allDeals = {




});
