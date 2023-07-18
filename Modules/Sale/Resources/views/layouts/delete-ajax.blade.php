<script type="text/javascript">
    function delete_row(route, row_id) {

        var table_row = '#row_' + row_id;
        var url = "{{url('')}}"+'/'+route+'/'+row_id;
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Delete'
          }).then((confirmed) => {
            if (confirmed.isConfirmed) {
                $.ajax({
                    type: 'delete',
                    url: url,
                })
                .done(function(response) {
                    location.reload();

                })
                .fail(function(error) {
                    Swal.fire('{{ _trans('common.opps') }}...', '{{ _trans('common.something_went_wrong_with_ajax') }}', 'error');
                })
            }
          });
    };
</script>
