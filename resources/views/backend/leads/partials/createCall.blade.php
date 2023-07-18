<form action="{{ @$data['url'] }}" method="POST">

    <div class="row">
        <div class="col-md-12">
            <div class="form-group mb-3">
                <label class="form-label" for="name">Subject<span class="text-danger">*</span></label>
                <input type="text" name="subject" class="form-control ot-form-control ot_input" placeholder="Subject" value="" required="">
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group mb-2">
                <label class="form-label" for="name">Message</label>
                <input type="text" name="message" class="form-control ot-form-control ot_input" placeholder="Message"  value="">
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group mb-3">
                <label class="form-label" for="name">Phone </label>
                <input type="text" name="phone" class="form-control ot-form-control ot_input" placeholder="Phone" value="">
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group mb-3">
                <label class="form-label" for="name">Date </label>
                <input type="text" name="date" class="form-control ot-form-control ot_input" placeholder="Phone" value="{{ date('Y-m-d') }}">
            </div>
        </div>


        <div class="col-md-12 d-flex justify-content-end">
                <button type="submit" class="crm_theme_btn ">Create</button>
        </div>
    </div>
</form>