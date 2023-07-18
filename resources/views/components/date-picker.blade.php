
<div>
    <div class="form-group mb-2 mr-2">
        @php
            $last_date=cal_days_in_month(CAL_GREGORIAN, date('m'), date('Y'));
            $last_date=date('m/'.$last_date.'/y');
        @endphp
        <input class="daterange-table-filter" type="text"  id="daterange" name="daterange" value="{{ date('m') }}/01/{{ date('Y') }} - {{ $last_date }}" />
    </div>
</div>
