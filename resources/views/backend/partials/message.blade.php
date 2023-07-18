<script>
    @foreach (session('flash_notification', collect())->toArray() as $message)
        @if($message['level'] == 'success')
            toastr.success("{{ Session::get('success') }}", "success"); 
            @else 
            toastr.error("{{ Session::get('error') }}", "error"); 

        @endif  
    @endforeach

    @if (Session::has('alert'))
        toastr.warning("{{ Session::get('alert') }}", "warning"); 
    @endif
    @if (Session::has('error'))
        toastr.error("{{ Session::get('error') }}", "error"); 
    @endif
    @if(Session::has('success'))
        toastr.success("{{ Session::get('success') }}", "success");
    @endif
    @if (Session::has('warning'))
        toastr.warning("{{ Session::get('warning') }}", "warning");
    @endif
    @if (Session::has('info'))
        toastr.info("{{ Session::get('info') }}", "info");
    @endif
</script>


<div class="modal fade lead-modal" id="callModal"  aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content data">
            <div class="modal-header modal-header-image mb-3">
                <h5 class="modal-title text-white" id="_modal_tile">{{ _trans('common.Add Call Logs') }} </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <i class="las la-times" aria-hidden="true"></i>
                </button>
            </div>
            <div class="modal-body" id="_modal_body">
            </div>
        </div>
    </div>
</div>

<script>
    function callModal(url, id){ 
        let data = {
            id: id,
            _token: "{{ csrf_token() }}",
            url: url
        };

        $.ajax({
            url: url,
            type: 'POST',
            data: data,
            success: function (data) {
                $('#_modal_tile').html(data.title);
                $('#_modal_body').html(data.body);
                $('#callModal').modal('show');
            },
            error: function (data) {
            }
        });
    }
</script>