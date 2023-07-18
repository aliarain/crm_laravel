function timeoutLoadTimeout(time){
    setTimeout(() => {
        window.location.reload();
    }, time);
}
export function dataAction(...values){
    if (values.length > 0) {
        if (values[0]['value']['type'] == 'alert') {
            function capitalize(s)
            {
                return s[0].toUpperCase() + s.slice(1);
            }
            Swal.fire({
                title: $('#are_you_sure').val(),
                text: "You want to " + values[0]['value']['action'] + " this record?",
                icon: values[0]['value']['icon'],
                showCancelButton: true,
                confirmButtonText: (capitalize(values[0]['value']['action'])),
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
            }).then((result) => {
                if (result.isConfirmed) {
                       http_Request(values).then(function(response){
                            if (response.status == 200) {                                
                                Toast.fire({
                                    icon: 'success',
                                    title: response.data.message,
                                    timer: 1500,
                                })
                                if (values[0]['value']['load'] == 'table') {
                                    pagination(page)
                                    return true;
                                }
                                timeoutLoadTimeout(1500)
                            }else {
                                Toast.fire({
                                    icon: 'error',
                                    title: response?.data?.message ?? 'Something went wrong.',
                                })
                            }

                        }).catch( err => {
                            if (err?.response?.data?.message) {
                                Toast.fire({
                                    title: err.response.data.message,
                                    type: 'error',
                                    icon: 'error',
                                    timer: 3000
                                });
                            }
                        })
                    } else if (
                        result.dismiss === Swal.DismissReason.cancel
                    ) {
                        Toast.fire({
                            iconColor: 'white',
                            icon: 'warning',
                            title: 'Cancel',
                        })
                    }
                }
            );       
        }else {
            Swal.fire({
                title: 'Something went wrong! Please try again later',
                type: 'error',
                icon: 'error',
                timer: 3000
            });

            return false;
        }
    }
}