// follow-next-side-step
$(".follow-next-step").on("click", function () {
  let closetTab = $(this).closest(".tab-section");
  let currentStepCount = closetTab.attr("step-count");
  let base_path=$('#base_path').val();

  closetTab.removeClass("show-section");
  closetTab.next().addClass("show-section");

  let images = [
    base_path+"/images/icon-white/welcome.svg",
    base_path+"/images/icon-white/enviroment.svg",
    base_path+"/images/icon-white/verification.svg",
    base_path+"/images/icon-white/database.svg",
    base_path+"/images/icon-white/admin.svg",
    base_path+"/images/icon-white/complete.svg",
  ];

  let nextStepCount = Number(currentStepCount);
  
  $(".step-with-border").eq(nextStepCount).addClass("initial");
  $(".step-with-border img").eq(nextStepCount).attr("src", images[nextStepCount]);
  $(".follow-next-step-side").each(function (index, el) {
    if (index + 1 == currentStepCount) {
      $(this).find(".step-with-border").removeClass("initial");
      $(this).find(".step-with-border").addClass("completed");
      $(this).find(".next-step-status-line").addClass("completed");
      $(this).find("img").attr("src", base_path+"/images/check-mark.svg");
    }
  });
});
