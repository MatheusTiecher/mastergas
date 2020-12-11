@if(session('notify'))
@foreach(session('notify') as $notify)
<script type="text/javascript">
	$( document ).ready(function() {
		var data = {!! $exnotify = $notify !!}; 
		$.notify({
			icon: data.icon,
			title: data.title,
			message: data.message
		},{
			type: data.type
		});
	});
</script>
@endforeach
@endif