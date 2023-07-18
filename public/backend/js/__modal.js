// Quick & dirty toggle to demonstrate modal toggle behavior
$('.modal-toggle').on('click', function(e) {
    e.preventDefault();
    $('.custom-modal').toggleClass('is-visible');
  });