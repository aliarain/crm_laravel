// Sticky Header

$(window).on('scroll',function() {
    let scroll = $(window).scrollTop();
    if (scroll < 445) {
        $(".ltn__header-sticky").removeClass("sticky-active");
    } else {
        $(".ltn__header-sticky").addClass("sticky-active");
    }
});
$(window).scroll(function(){
    if ($(window).scrollTop() >= 300) {
        $('.navbar').addClass('fixed-header');
    }
    else {
        $('.navbar').removeClass('fixed-header');
    }
  });

// Language Dropdwon Toggle

$(function() {
    $('.dropdown-toggle').on("click", function() { $(this).next('.dropdown-menu').slideToggle();
    });

    $(document).on("click", function(e)
    {
    var target = e.target;
    if (!$(target).is('.dropdown-toggle') && !$(target).parents().is('.dropdown-toggle'))
    //{ $('.dropdown').hide(); }
      { $('.dropdown-menu').slideUp(); }
    });
    });


// Portfolio

    function Filtering() {
        let buttons = document.querySelectorAll('.btns button')
        let blocks  = document.querySelectorAll('.single')
        buttons.forEach(button => {
            button.addEventListener('click', (e) => {
                blocks.forEach(block => {
                    block.classList.remove('active')
                    block.style.transform = `scale(0)`;
                    block.style.opacity = `0`;
                    block.style.visibility = `hidden`; 
                    block.style.display = `none`; 
                    block.style.width = `0`;
                    block.style.marginLeft = `0`;
                    block.style.marginRight = `0`;
                    block.style.height = `0`;
                })

                blocks.forEach(blk => {
                    if (e.target.dataset.menu == blk.dataset.menu) {
                        blk.classList.add('active')
                        blk.style.transform = `scale(1)`;
                        blk.style.opacity = `1`;
                        blk.style.visibility = `visible`; 
                        blk.style.display = `block`; 
                        blk.style.width = `100%`;
                        // blk.style.marginLeft = `5px`;
                        // blk.style.marginRight = `5px`;
                        blk.style.height = `100%`;
                    }
                })
            if (e.target.dataset.menu == 'all'){
                blocks.forEach(block => {
                    block.classList.add('active')
                        block.style.transform = `scale(1)`;
                        block.style.opacity = `1`;
                        block.style.visibility = `visible`; 
                        block.style.display = `block`; 
                        block.style.width = `100%`;
                        // block.style.marginLeft = `5px`;
                        // block.style.marginRight = `5px`;
                        block.style.height = `100%`;
                })
            }


            })
        })
    }
    Filtering()

// Management

function Filtering_management() {
    let buttons_management = document.querySelectorAll('.btns_management button')
    let blocks_management  = document.querySelectorAll('.single_management')
    buttons_management.forEach(button_management => {
        button_management.addEventListener('click', (e) => {
            blocks_management.forEach(block_management => {
                block_management.classList.remove('active')
                block_management.style.transform = `scale(0)`;
                block_management.style.opacity = `0`;
                block_management.style.visibility = `hidden`; 
                block_management.style.display = `none`; 
                block_management.style.width = `0`;
                block_management.style.marginLeft = `0`;
                block_management.style.marginRight = `0`;
                block_management.style.height = `0`;
            })

            blocks_management.forEach(blk_management => {
                if (e.target.dataset.menu == blk_management.dataset.menu) {
                    blk_management.classList.add('active')
                    blk_management.style.transform = `scale(1)`;
                    blk_management.style.opacity = `1`;
                    blk_management.style.visibility = `visible`; 
                    blk_management.style.display = `block`; 
                    blk_management.style.width = `100%`; 
                    // blk_management.style.marginLeft = `5px`;
                    // blk_management.style.marginRight = `5px`;
                    

                    blk_management.style.height = `100%`;
                }
            })
        if (e.target.dataset.menu == 'all_management'){
            blocks_management.forEach(block_management => {
                block_management.classList.add('active')
                    block_management.style.transform = `scale(1)`;
                    block_management.style.opacity = `1`;
                    block_management.style.visibility = `visible`; 
                    block_management.style.display = `block`; 
                    block_management.style.width = `100%`; 
                    // block_management.style.marginLeft = `5px`;
                    // block_management.style.marginRight = `5px`;
                    block_management.style.height = `100%`;
            })
        }


        })
    })
}
Filtering_management()