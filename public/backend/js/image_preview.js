//image view



$("#appSettings_company_logo").length > 0 ? appSettings_company_logo.onchange = evt => {
    const [file] = appSettings_company_logo.files
    if (file) {
        bruh.src = URL.createObjectURL(file)
    }
} : '';
$("#appSettings_company_logo_black").length > 0 ? appSettings_company_logo_black.onchange = evt => {
    const [file] = appSettings_company_logo_black.files
    if (file) {
        bruh_b.src = URL.createObjectURL(file)
    }
} : '';

$("#vehicle_front").length > 0 ? vehicle_front.onchange = evt => {
    const [file] = vehicle_front.files
    if (file) {
        bruh_front.src = URL.createObjectURL(file)
    }
} : '';
$("#appSettings_company_icon").length > 0 ? appSettings_company_icon.onchange = evt => {
    const [file] = appSettings_company_icon.files
    if (file) {
        bruh1.src = URL.createObjectURL(file)
    }
} : '';
$("#appSettings_company_banner").length > 0 ? appSettings_company_banner.onchange = evt => {
    const [file] = appSettings_company_banner.files
    if (file) {
        bruh2.src = URL.createObjectURL(file)
    }
} : '';
$("#stickerImage").length > 0 ? stickerImage.onchange = evt => {
    const [file] = appSettings_company_banner.files
    if (file) {
        sticker_img.src = URL.createObjectURL(file)
    }
} : '';

$('#driving_licence_file').length > 0 ? driving_licence_file.onchange = evt => {
    const [file] = driving_licence_file.files
    if (file) {
        driving_licence_file_preview.src = URL.createObjectURL(file)
    }
} : '';

$("#nid_card_file").length > 0 ? nid_card_file.onchange = evt => {
    const [file] = nid_card_file.files
    if (file) {
        nid_card_file_preview.src = URL.createObjectURL(file)
    }
} : '';
$("#passport_card_file").length > 0 ? passport_card_file.onchange = evt => {
    const [file] = passport_card_file.files
    if (file) {
        passport_card_file_preview.src = URL.createObjectURL(file)
    }
} : '';

$("#image_upload_input").length > 0 ? image_upload_input.onchange = evt => {
    const [file] = image_upload_input.files
    if (file) {
        uploaded_image_viewer.src = URL.createObjectURL(file)
    }
} : '';
 

//   end
$("#vehicle_front").length > 0 ? vehicle_front.onchange = evt => {
    const [file] = vehicle_front.files
    if (file) {
        bruh_front.src = URL.createObjectURL(file)
    }
} : '';
$("#vehicle_rare").length > 0 ? vehicle_rare.onchange = evt => {
    const [file] = vehicle_rare.files
    if (file) {
        bruh_rare.src = URL.createObjectURL(file)
    }
} : '';
$("#vehicle_side").length > 0 ? vehicle_side.onchange = evt => {
    const [file] = vehicle_side.files
    if (file) {
        bruh_side.src = URL.createObjectURL(file)
    }
} : '';
$("#vehicle_right").length > 0 ? vehicle_right.onchange = evt => {
    const [file] = vehicle_right.files
    if (file) {
        bruh_r_side.src = URL.createObjectURL(file)
    }
} : '';

$("#avatar_img").length > 0 ? avatar_img.onchange = evt => {
    const [file] = avatar_image.files
    if (file) {
        avatar_image.src = URL.createObjectURL(file)
    }
} : '';


$("#stickerSideImage").length > 0 ? stickerSideImage.onchange = evt => {
    const [file] = stickerSideImage.files
    if (file) {
        stickerSideImage_preview.src = URL.createObjectURL(file)
    }
} : '';

$("#stickerSideImageEdit").length > 0 ? stickerSideImageEdit.onchange = evt => {
    const [file] = stickerSideImageEdit.files
    if (file) {
        stickerSideImageEdit_preview.src = URL.createObjectURL(file)
    }
} : '';


//validity check

function validityCheck(id, file, btnId){
    let validFileType = file.type === 'image/jpeg' || file.type === 'image/png';

    if(validFileType){
        $(id).parents('.custom-image-upload-wrapper').removeClass('notValidImage');
    }else{
        $(id).parents('.custom-image-upload-wrapper').addClass('notValidImage');
    }

    if($('.notValidImage').length < 1){
        $(btnId).prop('disabled', false);
    }else{
        $(btnId).prop('disabled', true);
    }

}

//partners
$("#partnerImage").length > 0 ? partnerImage.onchange = evt => {
    const [file] = partnerImage.files

    if (file) {
        validityCheck('#partnerImage', file, '#partnerImageUploadBtn');
        partnerImage_prev.src = URL.createObjectURL(file)
    }
} : '';

$("#partnerImageEdit").length > 0 ? partnerImageEdit.onchange = evt => {
    const [file] = partnerImageEdit.files;
    let validFileType = file.type === 'image/jpeg' || file.type === 'image/png';

    if (file) {
        validityCheck('#partnerImageEdit', file, '#partnerImageEditBtn');
        if(validFileType){
            $('#partnerImageEdit').parents('.custom-image-upload-wrapper').removeClass('notValidImagee');
        }else{
            $('#partnerImageEdit').parents('.custom-image-upload-wrapper').addClass('notValidImagee');
        }

        if($('.notValidImagee').length < 1){
            $('#partnerImageEditBtn').prop('disabled', false);
        }else{
            $('#partnerImageEditBtn').prop('disabled', true);
        }

        partnerImageEdit_prev.src = URL.createObjectURL(file)
    }
} : '';


$(`#image1`).length > 0 ? image1.onchange = evt => {
    const [file] = image1.files;
    if (file) {
        validityCheck('#image1', file, '#vehicleTypeImageUploaderBtn');
        image1_prev.src = URL.createObjectURL(file)
    }
} : '';
$(`#image2`).length > 0 ? image2.onchange = evt => {
    const [file] = image2.files
    if (file) {
        validityCheck('#image2', file, '#vehicleTypeImageUploaderBtn');
        image2_prev.src = URL.createObjectURL(file)
    }
} : '';
$(`#image3`).length > 0 ? image3.onchange = evt => {
    const [file] = image3.files
    if (file) {
        validityCheck('#image3', file, '#vehicleTypeImageUploaderBtn');
        image3_prev.src = URL.createObjectURL(file)
    }
} : '';
$(`#image4`).length > 0 ? image4.onchange = evt => {
    const [file] = image4.files
    if (file) {
        validityCheck('#image4', file, '#vehicleTypeImageUploaderBtn');
        image4_prev.src = URL.createObjectURL(file)
    }
} : '';
$(`#image5`).length > 0 ? image5.onchange = evt => {
    const [file] = image5.files
    if (file) {
        validityCheck('#image5', file, '#vehicleTypeImageUploaderBtn');
        image5_prev.src = URL.createObjectURL(file)
    }
} : '';
$(`#image6`).length > 0 ? image6.onchange = evt => {
    const [file] = image6.files
    if (file) {
        validityCheck('#image6', file, '#vehicleTypeImageUploaderBtn');
        image6_prev.src = URL.createObjectURL(file)
    }
} : '';

$(`#image7`).length > 0 ? image7.onchange = evt => {
    const [file] = image7.files
    if (file) {
        validityCheck('#image7', file, '#vehicleTypeImageUploaderBtn');
        image7_prev.src = URL.createObjectURL(file)
    }
} : '';

$(`#image8`).length > 0 ? image8.onchange = evt => {
    const [file] = image8.files
    if (file) {
        validityCheck('#image8', file, '#vehicleTypeImageUploaderBtn');
        image8_prev.src = URL.createObjectURL(file)
    }
} : '';

// Image showing Modal
$(document).ready(function(){
	loadPreviews();

});

function loadPreviews(){
	$(".mini-thumb").on('click', function(){
  		console.log("Img url " + $(this).attr('src'));
      $("#photo_modal").attr("src", $(this).attr('src'));
  		$("#img_modal").modal("show");
  });
}

