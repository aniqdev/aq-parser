<div class="container">
<table class="table table-condensed" id="js-del-deligator">

	@if (is_array($res) AND $res) 
		<tr><th>del</th><th>№</th>
		@foreach ($res[0] as $key => $value)
			<th>{{$key}}</th>
		@endforeach
		</tr>
		@foreach ($res as $kr => $row)
			<tr><td><a href="?action=trustees&del={{$row['plati_id']}}" class="btn btn-danger btn-xs js-del">×</a></td><td>{{$kr+1}}</td>
			@foreach ($row as $kc => $col)
				<td>{{$col}}</td>
			@endforeach
			</tr>
		@endforeach
	@endif

</table>
</div>