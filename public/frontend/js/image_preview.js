$('#driving_licence_file').length > 0 ? driving_licence_file.onchange = evt => {
    const [file] = driving_licence_file.files
    if (file) {
        driving_licence_file_preview.src = URL.createObjectURL(file)
    }
} : '';

$("#appSettings_company_logo").length > 0 ? appSettings_company_logo.onchange = evt => {
    const [file] = appSettings_company_logo.files
    if (file) {
        bruh.src = URL.createObjectURL(file)
    }
} : '';

$("#stickerSideImage").length > 0 ? stickerSideImage.onchange = evt => {
    const [file] = stickerSideImage.files
    if (file) {
        bruh.src = URL.createObjectURL(file)
    }
} : '';

$("#nid_card_file").length > 0 ? nid_card_file.onchange = evt => {
    const [file] = nid_card_file.files
    if (file) {
        nid_card_file_preview.src = URL.createObjectURL(file)
    }
} : '';
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
$("#profile_image").length > 0 ? profile_image.onchange = evt => {
    const [file] = profile_image.files
    if (file) {
        avatar_image.src = URL.createObjectURL(file)
    }
} : '';

