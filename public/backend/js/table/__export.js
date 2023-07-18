

$(document).ready(function(){
    $(".btnExcelExport").on('click', function() {
        let table = document.getElementsByTagName("table");
        TableToExcel.convert(table[0], {
           name: `export.xlsx`, // fileName you could use any name
           sheet: {
              name: 'Sheet 1' // sheetName
           }
        });
    });

    $(".exportPDF").on('click', function() {
        $("#table").tableHTMLExport({type:'pdf',filename:'export.pdf', ignoreColumns: $('th:last-child')});
      
    });
    $(".exportCSV").on('click', function() {
        $("#table").tableHTMLExport({type:'csv',filename:'export.csv'});
      
    });
    $(".exportJSON").on('click', function() {
        $("#table").tableHTMLExport({type:'json',filename:'export.json'});
      
    });
    selectElementContents = (el) => {
        var body = document.body, range, sel;
        if (document.createRange && window.getSelection) {
            range = document.createRange();
            sel = window.getSelection();
            sel.removeAllRanges();
            try {
                range.selectNodeContents(el);
                sel.addRange(range);
            } catch (e) {
                range.selectNode(el);
                sel.addRange(range);
            }

            document.execCommand('copy');
            sel.removeAllRanges();
            Toast.fire({
                icon: 'success',
                title: 'Copied to clipboard',
                timer: 1500,
            })

        } else if (body.createTextRange) {
            range = body.createTextRange();
            range.moveToElementText(el);
            range.select();

            document.execCommand('copy');
            Toast.fire({
                icon: 'success',
                title: 'Copied to clipboard',
                timer: 1500,
            })


        }
    }
});