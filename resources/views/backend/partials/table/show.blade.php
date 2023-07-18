<div class="align-self-center">
    <label>
        <span class="mr-8">{{ _trans('sale.Show') }}</span>
        <select class="form-select d-inline-block" id="entries"  onchange="updateCurrentTableWithNumber('<?= @$path?>')" >
            <option value="10" @if(@$data['entries']==10) selected @endif>{{ _trans('number.Ten') }}</option>
            <option value="25" @if(@$data['entries']==25) selected @endif>{{ _trans('number.Twenty Five') }}</option>
            <option value="50" @if(@$data['entries']==50) selected @endif>{{ _trans('number.Fifty') }}</option>
            <option value="100" @if(@$data['entries']==100) selected @endif>{{ _trans('number.One Hundred') }}</option>
        </select>
        <span class="ml-8">{{ _trans('sale.Entries') }}</span>
    </label>
</div>
