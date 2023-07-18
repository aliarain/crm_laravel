<table class="table table-bordered {{ @$data['class'] }}" id="table">
    <thead class="thead">
        <tr>
            @if (@$data['checkbox'])
                <th class="sorting_asc">
                    <div class="check-box">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="all_check">
                        </div>
                    </div>
                </th>
            @endif


            @if (@$data['fields'])
                @foreach (@$data['fields'] as $field)
                    <th class="sorting_desc">{{ $field }}</th>
                @endforeach
            @endif
        </tr>
    </thead>

</table>
@if (@$data['url_id'])
    <input type="text" hidden id="{{ @$data['url_id'] }}" value="{{ @$data['table'] }}">
@endif
