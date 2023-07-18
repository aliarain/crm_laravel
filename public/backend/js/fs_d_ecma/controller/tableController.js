import { dataAction  } from "../action/__action.js";

let ids = [];
let data;
window.tableAction = (value,tab_url) => {
    

    switch(value){
        case 'delete':
            ids = [];
            $('.column_id:checked').each(function() {
                ids.push($(this).val());
            });
            data = {
                url : tab_url,
            };

            data["value"] = {
                ids: ids,
                type:'alert',
                action: 'delete',
                icon  : 'warning',                
                method: 'POST',
                load  : 'table',
                _token: _token,
            };
            if(ids.length > 0){
                let deleteAction = new dataAction(data);
                 $('#all_check').prop('checked', false)
            }else{
                Swal.fire({
                    title: 'Please select at least one row',
                    type: 'error',
                    icon: 'error',
                    timer: 3000,
                });
            }
            break;
        case 'active':
            ids = [];
            $('.column_id:checked').each(function() {
                ids.push($(this).val());
            });
            data = {
                url : tab_url,
            };

            data["value"] = {
                ids: ids,
                type:'alert',
                action: 'active',
                icon  : 'warning',                
                method: 'POST',
                load  : 'table',
                _token: _token,

            };
            if(ids.length > 0){
                dataAction(data);
                $('#all_check').prop('checked', false)
            }else{
                Swal.fire({
                    title: 'Please select at least one row',
                    type: 'error',
                    icon: 'error',
                    timer: 3000,
                });
            }
            break;
        case 'inactive':
            ids = [];
            $('.column_id:checked').each(function() {
                ids.push($(this).val());
            });
            data = {
                url : tab_url,
            };

            data["value"] = {
                ids: ids,
                type:'alert',
                action: 'inactive',
                icon  : 'warning',                
                method: 'POST',
                load  : 'table',
                _token: _token,
            };
            if(ids.length > 0){
                 dataAction(data);
                 $('#all_check').prop('checked', false)
            }else{
                Swal.fire({
                    title: 'Please select at least one row',
                    type: 'error',
                    icon: 'error',
                    timer: 3000,
                });
            }
            break;
        case 'complete':
            ids = [];
            $('.column_id:checked').each(function() {
                ids.push($(this).val());
            });
            data = {
                url : tab_url,
            };

            data["value"] = {
                ids: ids,
                type:'alert',
                action: 'complete',
                icon  : 'warning',                
                method: 'POST',
                load  : 'table',
                _token: _token,
            };
            if(ids.length > 0){
                 dataAction(data);
                 $('#all_check').prop('checked', false)
            }else{
                Swal.fire({
                    title: 'Please select at least one row',
                    type: 'error',
                    icon: 'error',
                    timer: 3000,
                });
            }
            break;
       
            default:
                break;
        }

}

