<div class="modal  fade lead-modal in" id="lead-modal" role="dialog" aria-labelledby="myLargeModalLabel">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content data">
            <div class="modal-header modal-header-image text-center">
                <h5 class="modal-title text-white">{{@$data['title']}} </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <i class="las la-times" aria-hidden="true"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="row p-0">
                    <div class="col-md-12">
                         <form action="{{ $data['url'] }}" method="post">
                            @csrf
                            <div class="form-group">
                                <label class="form-label">{{ _trans('common.Description') }} <span class="text-danger">*</span></label>
                                <textarea type="text" name="description" id="description" rows="5" class="form-control summernote mt-0 ot_input" placeholder="{{ _trans('common.Description') }}" required >{{ @$data['edit']->description ? $data['edit']->description :  old('description') }}</textarea>
                            </div>
                            <div class="form-group d-none">
                                <div class="checkbox checkbox-primary">
                                    <input type="checkbox" name="show_to_customer" value="1" {{ @$data['edit']->show_to_customer == 33 ? 'checked' : '' }} id="show_to_customer" >
                                        <label for="show_to_customer">{{ _trans('project.Visible to Customer') }}</label>
                                    </div>
                            </div>
                            <div class="form-group text-right d-flex justify-content-end">
                                <button type="submit" class="crm_theme_btn mt-3 pull-right " >{{ @$data['button'] }}</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


