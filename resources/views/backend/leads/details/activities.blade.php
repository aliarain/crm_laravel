<div class="table-content table-basic">
  <div class="card mb-3">
    <div class="card-body">
      <h2>{{ _trans('common.Activity Feed') }}</h2>
      <ol class="activity-feed">

        @foreach ($data['items'] as $item)
        <li class="feed-item">
          <div class="timeline-icon">
            <i class="las la-sms"></i>
          </div>
          <time class="date" datetime="9-25">{{ $item['date'] }}</time>
          <div class="timeLIne_content">
            <span class="badge badge-light-success fs-8 fw-bold mb-1">{{ $item['status'] }}</span>
            <p>{{ $item['message'] }}</p>
            <p>{{ _trans('lead.Lead created by') }} <span class="lead-creator">{{ $item['author'] }}</span> {{
              _trans('lead.for HRM Software') }}</p>
          </div>
        </li>
        @endforeach
      </ol>
    </div>
  </div>
</div>