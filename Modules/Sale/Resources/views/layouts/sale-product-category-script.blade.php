<script type="text/javascript">
    $("ul#setting").siblings('a').attr('aria-expanded','true');
        $("ul#setting").addClass("show");
        $("ul#setting #brand-menu").addClass("active");
    
        var brand_id = [];
    
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
    
        $( "#select_all" ).on( "change", function() {
            if ($(this).is(':checked')) {
                $("tbody input[type='checkbox']").prop('checked', true);
            }
            else {
                $("tbody input[type='checkbox']").prop('checked', false);
            }
        });
    
        $("#export").on("click", function(e){
            e.preventDefault();
            var brand = [];
            $(':checkbox:checked').each(function(i){
              brand[i] = $(this).val();
            });
            $.ajax({
               type:'POST',
               url:'/exportbrand',
               data:{
    
                    brandArray: brand
                },
               success:function(data){
                alert('Exported to CSV file successfully! Click Ok to download file');
                window.location.href = data;
               },error: function (error) {
                if (error.responseJSON.errors.name) {
                    $('#name').text(error.responseJSON.errors.name[0])
                }
            }
            });
        });
    
            $('.open-EditCategoryDialog').on('click', function() {
                var url = ""

                var id = $(this).data('id').toString();
                url = url.concat(id).concat("/edit");
    
                $.get(url, function(data) {
                    $("#editModal input[name='name']").val(data['name']);
                    $("#editModal select[name='parent_id']").val(data['parent_id']);
                    $("#editModal input[name='category_id']").val(data['id']);
                    $("#image").attr("src", data['image']);
    
                });
            });
    
    
</script>