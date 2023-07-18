<div class="modal  fade lead-modal in" id="lead-modal" role="dialog" aria-labelledby="myLargeModalLabel">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content data">
            <div class="modal-header text-center modal_headerBg_color">
                <h5 class="modal-title text-white">{{@$data['title']}} </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
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
                <div class="row p-0">
                    <div class="col-md-12">
                         <form action="{{ $data['url'] }}" method="post">
                            @csrf
                            <div class="form-group">
                                <label class="form-label">{{ _trans('common.Description') }} <span class="text-danger">*</span></label>
                                <textarea type="text" name="description" id="description" rows="5" class="form-control summernote" required >{{ @$data['edit']->description ? $data['edit']->description :  old('description') }}</textarea>
                            </div>
                            <div class="form-group d-none">
                                <div class="checkbox checkbox-primary">
                                    <input type="checkbox" name="show_to_customer" value="1" {{ @$data['edit']->show_to_customer == 33 ? 'checked' : '' }} id="show_to_customer" >
                                        <label for="show_to_customer">{{ _trans('project.Visible to Customer') }}</label>
                                    </div>
                            </div>
                            <div class="form-group text-right">
                                <button type="submit" class="btn btn-primary pull-right" >{{ @$data['button'] }}</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script >
    $('.summernote').summernote({
    height: 150,   //set editable area's height
    codemirror: { // codemirror options
        theme: 'monokai'
    }
    });
</script>


