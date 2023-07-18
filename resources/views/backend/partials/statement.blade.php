<div class="card">
    <div class="card-header">
        <div class="col-sm-9">
            <h3 class="mb-0 h6 text-left">{{ _trans('common.Account Statement Reports') }} :  <b>{{ @$data['date'] }}</b></h3>
        </div>
    </div>
    <div class="card-body card-margin" id="__accouont_statement">
        <div class="row">

            <div class="col-12">
                <h4 class="text-center">{{_trans('common.Income')}}</h4>
                <table class="table  table-bordered ">
                    <thead>
                        <th class="text-left">{{_trans('common.Title')}}</th>
                        <th width="25%" class="text-center">{{_trans('common.Amount')}}</th>
                    </thead>
                    <tbody>
                        @php $sum_total_income=0; @endphp
                        @foreach ($data['income'] as $category)


                            @php $total_income=0; @endphp

                            @php
                                $sumCategory = getExpanseByCategoryAll($category->id, $data['start_date'], $data['end_date'], 1);
                                $sum_total_income += $sumCategory;
                            @endphp
                            <tr>
                                <td class="text-left pl-6">{{_trans('common.Total')}} {{ $category->name }}</td>
                                <td class="text-center">{{ $sumCategory }}</td>
                            </tr>
                        @endforeach


                        <tr class="data-header">
                            <th class="text-center"><b>{{_trans('common.Total Amount')}} </b></th>
                            <th class="text-center">{{ @$sum_total_income }}</th>
                        </tr>
                    </tbody>

                </table>
            </div>
            <div class="col-12">
                <h4 class="text-center">{{_trans('common.Expenses')}}</h4>
                    <table class="table  table-bordered ">
                        <thead>
                            <th class="text-left">{{_trans('common.Title')}}</th>
                            <th width="25%" class="text-center">{{_trans('common.Amount')}}</th>
                        </thead>
                        <tbody>
                            @php $sum_total_expense=0; @endphp
                            @foreach ($data['expense'] as $category)


                                @php $total_expense=0; @endphp

                                @php
                                    $sumCategory = getExpanseByCategoryAll($category->id, $data['start_date'], $data['end_date'], 2);
                                    $sum_total_expense += $sumCategory;
                                @endphp
                                <tr>
                                    <td class="text-left pl-6">{{_trans('common.Total')}} {{ $category->name }}</td>
                                    <td class="text-center">{{ $sumCategory }}</td>
                                </tr>
                            @endforeach

                            <tr class="data-header">
                                <th class="text-center"><b>{{_trans('common.Total Amount')}} </b></th>
                                <th class="text-center">{{ @$sum_total_expense }}</th>
                            </tr>
                        </tbody>

                    </table>
                <h4 class="text-center">{{_trans('common.Benefits')}}</h4>
                    <table class="table  table-bordered ">
                        <thead>
                            <th class="text-left">{{_trans('common.Title')}}</th>
                            <th width="25%" class="text-center">{{_trans('common.Amount')}}</th>
                        </thead>
                        <tbody>
                            <tr class="data-header">
                                <td>{{_trans('common.Total Benefits')}}</td>
                                <td class="text-center">
                                    {{ @$sum_total_income  - @$sum_total_expense }}
                                </td>
                            </tr>
                        </tbody>

                    </table>
            </div>
        </div>
    </div>
</div>
