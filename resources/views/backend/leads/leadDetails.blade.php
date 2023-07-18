@extends('backend.layouts.app')
@section('title', @$data['title'])
@section('content')
@include('backend.leads.partials.lead_detials_menu')
 <div id="__lead_content"> </div>
@endsection
@section('script')
@include('backend.partials.table_js')

<script>
    function getFileNameFromPath(path) {
    var parts = path.split('/');
    return parts[parts.length - 1];
}


function downloadFile(url) {
    var filename = getFileNameFromPath(url);

  var link = document.createElement('a');
  link.setAttribute('href', url);
  link.setAttribute('download', filename);
  link.style.display = 'none';
  document.body.appendChild(link);
  link.click();
  document.body.removeChild(link);
}

</script>
@endsection