    // accordion
    $('.toggle').on("click", function(e) {
        e.preventDefault();
      let $this = $(this);
      console.log($this);
      if ($this.next().hasClass('show')) {
          $this.next().removeClass('show');
          $(".toggle").removeClass("minus-content")
          $(".toggle").addClass("plus-content");
          $this.next().slideUp(350);
      } else {
          $this.parent().parent().find('.inner').removeClass('show');
          $this.parent().parent().find('.inner').slideUp(350);
          $this.next().toggleClass('show');
          $(".toggle").removeClass("minus-content")
          $(".toggle").addClass("plus-content");
          $this.removeClass('plus-content');
          $this.addClass('minus-content');
          $this.next().slideToggle(350);
      }
  });
