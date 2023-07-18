
var page   = 1;
var table_data;
var __url = $('meta[name="base-url"]').attr("content");

function loader(){
    let loader = `<tbody>
                    <tr class="odd">
                        <td valign="top" colspan="${table_data['column'].length + 1}" class="dataTables_empty">
                            <div class="no-data-found-wrapper text-center ">
                                <div class="loading-circle">
                                    <div class="circle-segment segment-1"></div>
                                    <div class="circle-segment segment-2"></div>
                                    <div class="circle-segment segment-3"></div>
                                    <div class="circle-segment segment-4"></div>
                                    <div class="circle-segment segment-5"></div>
                                    <div class="circle-segment segment-6"></div>
                                    <div class="circle-segment segment-7"></div>
                                    <div class="circle-segment segment-8"></div> 
                                    <div class="circle-segment segment-9"></div>
                                    <div class="circle-segment segment-10"></div>
                                    <div class="circle-segment segment-11"></div>
                                    <div class="circle-segment segment-12"></div>   
                                </div>
                            </div>
                        </td>
                    </tr>
                </tbody>`;
    $("." + table_data['table_id']).find('thead').after(loader);
}
function emptyTable(){
    let html =`<tbody>
                <tr class="odd">
                    <td valign="top" colspan="${table_data['column'].length + 1}" class="dataTables_empty">
                        <div class="no-data-found-wrapper text-center ">
                            <img src="${__url}/public/assets/images/noDataFound.png" alt="noDataFound" class="mb-primary" width="200px" >
                            <p class="mb-0 text-center">${$('#nothing_show_here').val()}</p> 
                        </div>
                    </td>
                </tr>
            </tbody>`;
    $("." + table_data['table_id']).find('tbody').remove();
    $("." + table_data['table_id']).find('thead').after(html);
    $('.ot-pagination').remove();
}


function table(table_info, page = 1) {
// console.log(table_info);
    table_data = table_info;
    $.ajax({
        url: table_info['url'] + '?page=' + page ?? 1,
        type: "GET",
        data: table_info['value'],
        beforeSend: function(){
            loader();        
        },
        success: function (response) {
        // console.log(response);
            if(response?.data?.length > 0){
                let html = '';
                html += '<tbody class="tbody">';
                for (let i = 0; i < response.data.length; i++) {
                    html += '<tr>';
                    if ($('#all_check').length > 0) {
                        html += `<td>
                                <div class="check-box">
                                    <div class="form-check">
                                        <input class="form-check-input column_id" id="column_${response?.data[i]['id']}" onclick="columnID(${response?.data[i]['id']})" type="checkbox" name="column_id[]" value="${response?.data[i]['id']}"/>
                                    </div>
                                </div>
                            </td>`;
                    }
                    for (let j = 0; j < table_info['column'].length; j++) {
                    if (table_info['column'][j] == 'id') {                            
                        html += '<td>' + (i + 1) + '</td>';
                    } else {                            
                        html += '<td>' + response.data[i][table_info['column'][j]] + '</td>';
                    }
                    }
                    html += '</tr>';
                }
                html += '</tbody>';
                $("." + table_info['table_id']).find('tbody').remove();
                $("." + table_info['table_id']).find('thead').after(html);
                $('.ot-pagination').remove();
                $('.table-responsive').after(response?.pagination?.pagination_html)
                // response?.pagination?.total_pages - response?.pagination?.current_page > 0 ?  page ++ : page = 1;
            }else{
                emptyTable();                 
            }
        },
        error: function (error) {
            emptyTable();      
            if (error.responseJSON.message) {                    
                Swal.fire({
                    title: error.responseJSON.message,
                    type: 'error',
                    icon: 'error',
                    timer: 3000
                });
            };
        },
    });
}

function pagination(page_no){
page = page_no ?? 1;
table(table_data, page);
}
