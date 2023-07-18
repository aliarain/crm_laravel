var button = document.getElementById("filter-button");
var container = document.getElementById("filter-container");
var input = document.querySelectorAll("input");
var url = $("#url").val();
// tokens
var _token = $('meta[name="csrf-token"]').attr("content");



// avoid dropdown menu close on inside click
$(".disable-menu-hover").on("click", function(e) {
    e.stopPropagation();
});

// Accordion
$(document).ready(function() {
    $(".set > a").on("click", function() {
        if ($(this).hasClass("active")) {
            $(this).removeClass("active");
            $(this)
                .siblings(".content_cus")
                .slideUp(200);
            $(".set > a i")
                .removeClass("fa-minus")
                .addClass("fa-plus");
        } else {
            $(".set > a i")
                .removeClass("fa-minus")
                .addClass("fa-plus");
            $(this)
                .find("i")
                .removeClass("fa-plus")
                .addClass("fa-minus");
            $(".set > a").removeClass("active");
            $(this).addClass("active");
            $(".content_cus").slideUp(200);
            $(this)
                .siblings(".content_cus")
                .slideDown(200);
        }
    });
});

// sidebar active menu

$(document).ready(function() {
    $(".clickable-sidebar").on("click", function() {
        let sub_menu = $(".nav-item .active").parent(".submenu");
        if (sub_menu.hasClass("show")) {
            sub_menu.removeClass("show");
        } else {
            sub_menu.addClass("show");
        }
    });

    // active class add in parent menu
    $('.submenu.show').parent().addClass('open');

});


//search user information
$("#__global_search_option").on("keyup", () => userInformation());

$(document).on('click', function(e){
  $(".admin-dashboard-search-result-container").hide();
  $("#__global_search_option").val('');
})

$(".admin-dashboard-search-result-container, .global-search-option, .user-search-btn").on('click', function(e){
  e.stopPropagation();
})


userInformation = () => {
    setTimeout(function() {
        call();
    }, 1000);

    function call() {
        $.ajax({
            type: "POST",
            dataType: "html",
            data: {
                search: $("#__global_search_option").val(),
                _token: _token
            },
            url: url + "/" + "dashboard/user-search",

            beforeSend: function(data) {
                $("#__search_result_list").empty();
            },

            success: function(data) {
                $(".admin-dashboard-search-result-container").show();
                $(".admin-dashboard-search-results").empty();
                data = JSON.parse(data);
                let items = [];
                if(data.data[0].length === 0){
                    $('#__search_result_list').append('<li><h6 class="search-list-empty-custom">Search List Empty</h6></li>')
                }else{
                    $.each(data.data[0], function(index, value) {
                        items.push(
                          `<li class="admin-dashboard-search-item">
                            <a href="${url}/dashboard/${value.type === 'Driver' ? `drivers/panel/${value.id}` : `clients/panel/${value.id}`}" class="data-item-container">
                              <div class="data-item">
                                  <h6>Name: </h6>
                                  <p>${value.name}</p>
                              </div>
                              <div class="data-item">
                                  <h6>Phone: </h6>
                                  <p>${value.phone}</p>
                              </div>
                              <div class="data-item">
                                  <h6>Email: </h6>
                                  <p>${value.email}</p>
                              </div>
                            </a>
                          </li>`
                        );
                    });
    
                    $("#__search_result_list").append(items);
                }
            },
            error: function(data) {}
        });
    }
};
