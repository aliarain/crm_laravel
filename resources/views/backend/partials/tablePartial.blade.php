<table id="table" class="table mb-0 w-100 {{ @$data['class'] }} table-bordered  custom-table">
    <thead class="table-bordered bg-light">
        <tr class="data-header">
            @if (@$data['fields'])
                @foreach (@$data['fields'] as $field)
                  <th>{{ $field }}</th>
                @endforeach
            @endif
        </tr>
    </thead>

</table>
