$(function(){
    if($("input[name=phone]").val().trim().length <= 0){
        $(".leading_agency_btn").attr("disabled", false);
        $(".campaign_idea_btn").attr("disabled", false);
        $(".contact_btn").attr("disabled", false);
    }
    $("input[name=phone]").on('input', function(){
        let phoneNumber = $(this).val().trim();
        if(phoneNumber.length > 0){
            // let matched = phoneNumber.match(/^(?:\+88|88)?(01[3-9]\d{8})$/);
            let matched = phoneNumber.match(/^(01[3-9]\d{8})$/);
            if(!matched){
                $(".legal_phone").text("Phone Number is not valid.")
                $(".leading_agency_btn").attr("disabled", true);
                $(".campaign_idea_phone").text("Phone Number is not valid.")
                $(".campaign_idea_btn").attr("disabled", true);
                $(".contact_phone").text("Phone Number is not valid.")
                $(".contact_btn").attr("disabled", true);
            }else{
                $(".legal_phone").text("")
                $(".leading_agency_btn").attr("disabled", false);
                $(".campaign_idea_phone").text("")
                $(".campaign_idea_btn").attr("disabled", false);
                $(".contact_phone").text("")
                $(".contact_btn").attr("disabled", false);
            }
        }else{
            $(".leading_agency_btn").attr("disabled", true);
            $(".legal_phone").text("");
            $(".campaign_idea_btn").attr("disabled", true);
            $(".campaign_idea_phone").text("");
            $(".contact_btn").attr("disabled", true);
            $(".contact_phone").text("");
        }
    })
})
