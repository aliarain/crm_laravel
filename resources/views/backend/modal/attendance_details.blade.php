<div class="modal fade bd-example-modal-xl" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered  modal-xl">
        <div class="modal-content data">
            <div class="modal-header text-center modal_headerBg_color">
                <h5 class="modal-title text-white">{{ $data['title'] }}</h5>
                <button type="button" class="close text-white break_close" data-dismiss="modal" aria-label="Close">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24"
                        stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path stroke="none" d="M0 0h24v24H0z" />
                        <line x1="18" y1="6" x2="6" y2="18" />
                        <line x1="6" y1="6" x2="18" y2="18" />
                    </svg>
                </button>
            </div>
            <div class="modal-body">
                <div class="row p-5">
                    <div class="col-md-12">

                        <table class="table table-bordered" >
                            
                            <thead>
                                <tr class="border-bottom-custom">
                                    <th colspan="3" class="text-center">{{ _trans('attendance.Check In') }}</th>
                                    <th colspan="3" class="text-center">{{ _trans('attendance.Check Out') }}</th>
                                </tr>
                                <tr class="border-bottom-custom">
                                    <th>{{ _trans('common.Time') }}</th>
                                    <th>{{ _trans('common.Reason') }}</th>
                                    <th>{{ _trans('common.Location') }}</th>
                                    <th>{{ _trans('common.Time') }}</th>
                                    <th>{{ _trans('common.Reason') }}</th>
                                    <th>{{ _trans('common.Location') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($data['attendance'] as $attendance)
                                <tr>
                                    <td>{{ @dateTimeIn($attendance->check_in) }}</td>
                                    <td>{{ @$attendance->lateInReason->reason }}</td>
                                    <td>{{ @$attendance->check_in_location }}</td>

                                    <td>{{ dateTimeIn($attendance->check_out) }}</td>
                                    <td>{{ @$attendance->earlyOutReason->reason }}</td>
                                    <td>{{ @$attendance->check_out_location }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
