"use strict";

$('.select2').select2()

$('#table').DataTable({
    "paging": true,
    "lengthChange": true,
    "searching": true,
    "ordering": false,
    "info": true,
    "autoWidth": false,
    "responsive": true,
  });

  document.getElementById("printBtn").onclick = function() {
      window.print();
  }
