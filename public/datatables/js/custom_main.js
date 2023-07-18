function ajaxTable(data) {
    $('#table').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
         url: data['url'],
         data: data['value']
         },
        //  bLengthChange: true,
         "bDestroy": true,
         language: {
             paginate: {
                 next: "<i class='ti-arrow-right'></i>",
                 previous: "<i class='ti-arrow-left'></i>"
             },
             processing: '<i class="fa fa-spinner fa-spin fa-3x fa-fw"></i><span class="sr-only">Loading...</span> ',
         },
         dom: 'Blfrtip',
         lengthMenu:[
             [10,25,100,-1],
             ['10 rows','25 rows','100 rows','Show all']
         ],
         buttons: [
             {
                 extend: 'copyHtml5',
                 text: '<i class="fa fa-files-o"></i>',
                 titleAttr: 'Copy',
                 exportOptions: {
                     columns: ':visible',
                 }
             },
             {
                 extend: 'excelHtml5',
                 text: '<i class="fa fa-file-excel-o"></i>',
                 titleAttr: 'Excel',
                 exportOptions: {
                     columns: ':visible',
                     order: 'applied'
                 }
             },
             {
                 extend: 'csvHtml5',
                 text: '<i class="fa fa-file-text-o"></i>',
                 titleAttr: 'CSV',
                 exportOptions: {
                     columns: ':visible',
                 }
             },
             {
                 extend: 'pdfHtml5',
                 text: '<i class="fa fa-file-pdf-o"></i>',
                 titleAttr: 'PDF',
                 orientation: 'landscape',
                 pageSize: 'A5',
                   alignment: 'center',
                   header: true,
                   margin: 20,
             },
             'colvis'
             
         ],
          responsive: true,
          pageLength: 25,
          deferRender: true,        
          fixedColumns: true,
          columns: data['column'],
          order: data['order'],
          searching: true,
      
      });
}