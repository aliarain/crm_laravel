@php $rating = $data->rating;@endphp
@foreach (range(1, 5) as $i)
    @if ($rating > 0.5)
        <i class="fas fa-star far"></i>
    @elseif($rating <= 0)
        <i class="far fa-star"></i>
    @else
        <i class="fas fa-star-half-alt far"></i>
    @endif
    @php $rating--;@endphp
@endforeach
