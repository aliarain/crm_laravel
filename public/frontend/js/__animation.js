new WOW().init();

wow = new WOW({
    boxClass:     'wow',      // default
    animateClass: 'animated', // default
    offset:       0,          // default
    mobile:       true,       // default
    live:         true        // default
})

wow.init();

// Select the node that will be observed for mutations
let fullCarRotation = document.getElementById('fullCarRotation');
let coverageSwiper = document.getElementById('coverageSwiper');

let observer = new IntersectionObserver((entries, observer) => { 
    entries.forEach(entry => {
        if(entry.isIntersecting){
         $(".first-coverage-car").addClass("animate-car");
         $(".coverage-swiper").addClass("coverage-swipper-display")
          observer.unobserve(entry.target);
        }
      });
}, {threshold: 1});

fullCarRotation && observer.observe(fullCarRotation);


$("#coverageSwiper").on("mouseover", function(){
  $("#coverageSwiper").hide();
});

$("#fullCarRotation").on("mousedown", function(){
  $("#coverageSwiper").hide();
});