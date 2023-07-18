'use strict';

export const revenueChart = (data) => {
  if (document.querySelector('#revenueChart')) {
    var chartDom = document.getElementById('revenueChart');
    var myChart = echarts.init(chartDom);
    var option = {
      color: ['#0063F7', '#00F7BF'],

      tooltip: {
        trigger: 'axis',
        axisPointer: {
          type: 'cross',
          crossStyle: {
            color: '#999',
          },
        },
        formatter: function (params) {
          var res = params[0].name;
          for (var i = 0, l = params.length; i < l; i++) {
            res +=
              '<br/>' +
              params[i].seriesName +
              ' : ' +
              currency_symbol +
              ' ' +
              params[i].value;
          }
          return res;
        },
      },
      legend: {
        orient: 'horizontal',
        x: 'center',
        y: 'bottom',
        textStyle: {
          color: '#6f767e',
          fontFamily: 'Lexend',
          fontSize: '14px',
          fontWeight: '500',
        },
        padding: [0, 0, 0, 0],
        itemGap: 20,
        itemWidth: 14,
        itemHeight: 14,
      },
      grid: {
        left: 0,
        right: 10,
        bottom: 24,
        containLabel: true,
      },
      xAxis: [
        {
          type: 'category',
          data: data?.date_array,
          axisTick: {
            alignWithLabel: true,
          },
          axisLabel: {
            fontSize: '14',
            fontFamily: 'Lexend',
            fontWeight: '400',
          color: '#6f767e',
          },
          axisLine: {
            lineStyle: {
              color: '#999',
            },
          },
        },
      ],
      yAxis: [
        {
          type: 'value',
          axisLabel: {
            fontSize: '14',
            fontFamily: 'Lexend',
            fontWeight: '400',
          color: '#6f767e',
            formatter: currency_symbol + ' {value} ',
          },
          nameLocation: 'middle',
          nameGap: 50,
        },
      ],
      toolbox: {
        show: true,
        feature: {
          dataView: { show: true, readOnly: false },
          magicType: { show: true, type: ['line', 'bar', 'circle'] },
          restore: { show: true },
          saveAsImage: { show: true },
        },
        itemSize: 14,
        itemGap: 20,
        iconStyle: {
          borderColor: '#6f767e',
        },
        // make a margin bottom
      },
      series: data?.categories,
    };
    option && myChart.setOption(option);
    $(window).on('resize', function () {
      setTimeout(function () {
        myChart.resize();
      }, 500);
    });
  }
};
export const payrollChart = (data) => {
  if (document.querySelector('#payrollChart')) {
    var chartDom = document.getElementById('payrollChart');
    var myChart = echarts.init(chartDom);
    var option = {
      color: ['#0063F7', '#00F7BF'],
      tooltip: {
        trigger: 'axis',
        axisPointer: {
          type: 'cross',
          crossStyle: {
            color: '#999',
          },
        },
        formatter: function (params) {
          var res = params[0].name;
          for (var i = 0, l = params.length; i < l; i++) {
            res +=
              '<br/>' +
              params[i].seriesName +
              ' : ' +
              currency_symbol +
              ' ' +
              params[i].value;
          }
          return res;
        },
      },

      grid: {
        left: 0,
        right: 0,
        bottom: 0,
        containLabel: true,
      },
      xAxis: [
        {
          type: 'category',
          data: data?.date_array,
          axisTick: {
            alignWithLabel: true,
          },
          axisLabel: {
            fontSize: '14',
            fontFamily: 'Lexend',
            fontWeight: '400',
          color: '#6f767e',
          },
          axisLine: {
            lineStyle: {
              color: '#999',
            },
          },
        },
      ],
      yAxis: [
        {
          type: 'value',
          axisLabel: {
            fontSize: '14',
            fontFamily: 'Lexend',
            fontWeight: '400',
          color: '#6f767e',
            formatter: currency_symbol + ' {value} ',
          },
          nameLocation: 'middle',
          nameGap: 50,
        },
      ],
      toolbox: {
        feature: {
          dataView: { show: true, readOnly: false },
          magicType: { show: true, type: ['line', 'bar', 'circle'] },
          restore: { show: true },
          saveAsImage: { show: true },
        },
        top: 0,
        right: 0,
        itemSize: 14,
        itemGap: 20,
        iconStyle: {
          borderColor: '#6f767e',
        },
      },
      series: data?.categories,
    };

    option && myChart.setOption(option);
    $(window).on('resize', function () {
      setTimeout(function () {
        myChart.resize();
      }, 500);
    });
  }
};

export const attendanceChart = (data) => {
  if (document.querySelector('#attendance_summary_chart')) {
    var chartDom = document.getElementById('attendance_summary_chart');
    var myChart = echarts.init(chartDom);
    var option = {
      tooltip: {
        trigger: 'item',
      },
      legend: {
        orient: 'horizontal',
        bottom: 0,
        textStyle: {
          color: '#6f767e',
          fontFamily: 'Lexend',
          fontSize: '14px',
          fontWeight: '500',
        },
        itemGap: 20,
        itemWidth: 14,
        itemHeight: 14,
      },
      toolbox: {
        feature: {
          dataView: { show: true, readOnly: false },
          magicType: { show: false, type: ['line', 'bar', 'circle'] },
          restore: { show: false },
          saveAsImage: { show: true },
        },
        top: 0,
        right: 0,
        itemSize: 14,
        itemGap: 20,
        iconStyle: {
          borderColor: '#6f767e',
        },
      },
      series: [
        {
          type: 'pie',
          radius: '60%',
          data: data,
            label: {
              show: true,
              position: 'outside',
              color: '#6f767e',
              fontStyle: 'normal',
              fontWeight: ' 500',
              fontFamily: 'Lexend',
              fontSize: 14,
              backgroundColor: 'transparent',
              borderWidth: 0,
              borderType: 'solid',
              borderDashOffset: 0,
              borderRadius: 0,
              padding: 0,
              shadowColor: 'transparent',
              shadowBlur: 0,
              shadowOffsetX: 0,
              shadowOffsetY: 0,
              textBorderType: 'solid',
              textBorderDashOffset: 0,
              textShadowColor: 'transparent',
              textShadowBlur: 0,
              textShadowOffsetX: 0,
              textShadowOffsetY: 0,
              overflow: 'none',
            },
          },
        ],
    };
    option && myChart.setOption(option);
    $(window).on('resize', function () {
      setTimeout(function () {
        myChart.resize();
      }, 500);
    });
  }
};
export const userChart = (data) => {
  if (document.querySelector('#visited_customer_chart')) {
    var chartDom = document.getElementById('visited_customer_chart');
    var myChart = echarts.init(chartDom);
    var option = {
      legend: {
        type: 'scroll',
        orient: 'vertical',
        right: 10,
        top: 20,
        bottom: 20,
        textStyle: {
          color: '#6f767e',
        },
      },
      toolbox: {
        feature: {
          dataView: { show: true, readOnly: false },
          magicType: { show: false, type: ['line', 'bar', 'circle'] },
          restore: { show: false },
          saveAsImage: { show: true },
        },
        top: 0,
        right: 0,
        itemSize: 14,
        itemGap: 20,
        iconStyle: {
          borderColor: '#6f767e',
        },
        animation: true,
      },
      series: [
        {
          type: 'pie',
          radius: '100%',
          center: ['40%', '50%'],
          color: ['#5669FF', '#00F7BF', '#FFB64D', '#FF5370'],
          data: data,
          emphasis: {
            itemStyle: {
              shadowBlur: 20,
              shadowOffsetX: 20,
              shadowColor: 'rgba(0, 0, 0, 0.5)',
            },
            label: {
              show: true,
              fontSize: '20',
              fontWeight: 'bold',
            },
          },
          bottom: 100,
        },
      ],
    };

    option && myChart.setOption(option);
    $(window).on('resize', function () {
      setTimeout(function () {
        myChart.resize();
      }, 500);
    });
  }
};

export const appointmentSummary = (data, id) => {
  var content = '';
  if (data.length > 0) {
    data?.forEach((e) => {
      content += '<tr>';
      content += '<td>' + e?.title + '</td>';
      content += '<td>' + e?.with + '</td>';
      content += '<td>' + e?.location + '</td>';
      content += '<td>' + e?.date_time + '</td>';
      content += '</tr>';
    });
  } else {
    content += `<tr class="bg-transparent">
                  <td valign="top" colspan="4" class="dataTables_empty">
                      <div class="no-data-found-wrapper text-center ">
                          <img src="${$('meta[name="base-url"]').attr(
                            'content'
                          )}/public/assets/images/noDataFound.png" alt="noDataFound" class="mb-primary" width="100">
                          <p class="mb-0 text-center">${$(
                            '#nothing_show_here'
                          ).val()}</p> 
                      </div>
                  </td>
              </tr>`;
  }

  $('#' + id).append(content);
};
export const projectSummary = (data) => {
  if (document.querySelector('#project_summary_chart')) {
    var chartDom = document.getElementById('project_summary_chart');
    var myChart = echarts.init(chartDom);
    var option = {
  
      legend: {
        bottom: 'bottom',
        type: 'scroll',
        orient: 'horizontal',
        bottom: 20,
        textStyle: {
          color: '#6f767e',
          fontFamily: 'Lexend',
          fontSize: '14px',
          fontWeight: '500',
        },
        itemGap: 20,
        itemWidth: 14,
        itemHeight: 14,
      },
      toolbox: {
        show: true,
        feature: {
          mark: { show: true },
          dataView: { show: true, readOnly: false },
          restore: { show: true },
          saveAsImage: { show: true },
        },
      },
      toolbox: {
        feature: {
          dataView: { show: true, readOnly: false },
          magicType: { show: false, type: ['line', 'bar', 'circle'] },
          restore: { show: false },
          saveAsImage: { show: true },
        },
        top: 0,
        right: 0,
        itemSize: 14,
        itemGap: 20,
        iconStyle: {
          borderColor: '#6f767e',
        },
        animation: true,
      },
      series: [
        {
          type: 'pie',
          radius: '60%',
          color: ['#7f58fe', '#f69407', '#44bcfc', '#FF5370'],
          data: data,
          label: {
            show: true,
            position: 'outside',
            color: '#6f767e',
            fontStyle: 'normal',
            fontWeight: ' 500',
            fontFamily: 'Lexend',
            fontSize: 14,
            backgroundColor: 'transparent',
            borderWidth: 0,
            borderType: 'solid',
            borderDashOffset: 0,
            borderRadius: 0,
            padding: 0,
            shadowColor: 'transparent',
            shadowBlur: 0,
            shadowOffsetX: 0,
            shadowOffsetY: 0,
            textBorderType: 'solid',
            textBorderDashOffset: 0,
            textShadowColor: 'transparent',
            textShadowBlur: 0,
            textShadowOffsetX: 0,
            textShadowOffsetY: 0,
            overflow: 'none',
          },
          
        },
      ],
    };

    option && myChart.setOption(option);
    $(window).on('resize', function () {
      setTimeout(function () {
        myChart.resize();
      }, 500);
    });
  }
};
export const taskSummary = (data) => {
  if (document.querySelector('#task_summary_chart')) {
    var chartDom = document.getElementById('task_summary_chart');
    var myChart = echarts.init(chartDom);
    var option = {
      legend: {
        bottom: 'bottom',
        type: 'scroll',
        orient: 'horizontal',
        bottom: 20,
        textStyle: {
          color: '#6f767e',
          fontFamily: 'Lexend',
          fontSize: '14px',
          fontWeight: '500',
        },
        itemGap: 20,
        itemWidth: 14,
        itemHeight: 14,
      },
      toolbox: {
        feature: {
          dataView: { show: true, readOnly: false },
          magicType: { show: false, type: ['line', 'bar', 'circle'] },
          restore: { show: false },
          saveAsImage: { show: true },
        },
        top: 20,
        right: 0,
        itemSize: 14,
        itemGap: 20,
        iconStyle: {
          borderColor: '#6f767e',
        },
        animation: true,
      },
      series: [
        {
          type: 'pie',
          radius: '60%',
          itemStyle: {
            borderRadius: 2,
          },

          data: data,
          label: {
            show: true,
            position: 'outside',
            color: '#6f767e',
            fontStyle: 'normal',
            fontWeight: ' 500',
            fontFamily: 'Lexend',
            fontSize: 14,
            backgroundColor: 'transparent',
            borderWidth: 0,
            borderType: 'solid',
            borderDashOffset: 0,
            borderRadius: 0,
            padding: 0,
            shadowColor: 'transparent',
            shadowBlur: 0,
            shadowOffsetX: 0,
            shadowOffsetY: 0,
            textBorderType: 'solid',
            textBorderDashOffset: 0,
            textShadowColor: 'transparent',
            textShadowBlur: 0,
            textShadowOffsetX: 0,
            textShadowOffsetY: 0,
            overflow: 'none',
          },
        },
      ],
    };

    option && myChart.setOption(option);
    $(window).on('resize', function () {
      setTimeout(function () {
        myChart.resize();
      }, 500);
    });
  }
};
