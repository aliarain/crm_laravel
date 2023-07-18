<script type="text/javascript">
    $("ul#expense").siblings('a').attr('aria-expanded', 'true');
    $("ul#expense").addClass("show");
    $("ul#expense #exp-cat-menu").addClass("active");

    var expense_category_id = [];
    var user_verified = <?php echo json_encode(env('USER_VERIFIED')); ?>;

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('#genbutton').on("click", function() {
        $.get('gencode', function(data) {
            $("input[name='code']").val(data);
        });
    });

    $('.open-Editexpense_categoryDialog').on('click', function() {
        var url = ""
        var id = $(this).data('id').toString();
        url = url.concat(id).concat("/edit");
        $.get(url, function(data) {
            $("input[name='code']").val(data['code']);
            $("input[name='name']").val(data['name']);
            $("input[name='expense_category_id']").val(data['id']);
        });
    });
</script>