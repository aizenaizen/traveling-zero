<title>Path Finder Presentation</title>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">

<input type="hidden" class="matrix" value="{{json_encode($array)}}">
<input type="hidden" class="point_one" value="{{json_encode($point_one)}}">
<input type="hidden" class="point_two" value="{{json_encode($point_two)}}">
<input type="hidden" class="url" value="/api/{{$algorithm}}">
<div class="" style="margin: 20px; 20px;">
	Algorithm: {{strtoupper($algorithm)}}
	<div class="row">
		<div class="col-sm">
			<table class="table-matrix" style="border: 5px solid gray; float: left; margin-right: 20px">
				@foreach($array as $i => $row)
					<tr>
						@foreach($row as $j => $col)
							<td align="center" style="border: 1px solid black; font-family: monospace; font-size: 50px; width: 50px; height: 50px;" class="{{(($i == $point_one[0] && $j == $point_one[1]) || ($i == $point_two[0] && $j == $point_two[1])) ? 'point' : 'path'}}">
								@if($i == $point_one[0] && $j == $point_one[1])
									S
								@elseif($i == $point_two[0] && $j == $point_two[1])
									E
								@else
									{{$col}}
								@endif
							</td>
						@endforeach
					</tr>
				@endforeach
			</table>
			<div style="float: left">
				<div style="margin-bottom: 10px;"><a href="" class="change pull-left btn btn-primary">Shuffle</a></div>
				<div><button class="solve pull-left btn btn-primary">Solve</button></div>
			</div>
		</div>
	</div>
	<div class="result" style="margin-top: 20px;">
</div>

</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
	$(document).ready(function(){
		$('.point').css('background-color','#c6d2f6');
		$('.change').click(function(){
			
		});
		$('.solve').click(function(){
			$('.point').css('background-color','#c6d2f6');
			$('.path').css('background-color','white');
			var url = $('.url').val();
			var matrix = $('.matrix').val();
			var point_one = $('.point_one').val();
			var point_two = $('.point_two').val();
			
			var body = {matrix: matrix, point_one: point_one, point_two: point_two};
			var explored = '';
			$.post(url, body, function(data) {
				var result = JSON.parse(data);
				
				var done_search = false;
				for(var i = 0; i < result.explored_nodes.length; i++) {
					let ii = i;
					setTimeout(function timer() {
						let exp = result.explored_nodes[ii];
						let node = exp.replace('(', '').replace(')', '').split(',');
						$('.table-matrix').find('tr').eq(node[0]).find('td').eq(node[1]).css('background-color','#fdccd4');
						if(result.explored_nodes.length == (ii+1)) path()
					}, i * {{400/count($array)}});
				}
				
				function path() {
					if(result.algorithm == 'dfs') {
						$('.result').html("<h6>Matrix Status</h6>");
						$('.result').append("<div>&nbsp;*&nbsp;"+result.message+"</div><br>");
						$('.result').append("<h6>Nodes Explored</h6>");
						$('.result').append("<div>&nbsp;*&nbsp;"+result.explored_nodes+"</div><br>");
						var path_color = 'gray';
						var point_color = 'red';
						if (result.path == true) {
							var path_color = '#a4d2ac';
							var point_color = '#7bad56';
						}
						$('.table-matrix').find('tr').eq({{$point_one[0]}}).find('td').eq({{$point_one[1]}}).css('background-color',path_color);
						$('.table-matrix').find('tr').eq({{$point_two[0]}}).find('td').eq({{$point_two[1]}}).css('background-color',path_color);
					} else {
						$('.result').html("<h6>Matrix Status</h6>");
						$('.result').append("<div>&nbsp;*&nbsp;"+result.message+"</div><br>");
						$('.result').append("<h6>Nodes Explored</h6>");
						$('.result').append("<div>&nbsp;*&nbsp;"+result.explored_nodes+"</div><br>");
						if (typeof result.shortest_distance !== 'undefined') {
							$('.result').append("<h6>Node Count of Shortest Path</h6>");
							$('.result').append("<div>&nbsp;*&nbsp;"+result.shortest_distance+"</div><br>");
						}
						var path_color = 'gray';
						var point_color = 'red';
						if (result.path == true) {
							var path_color = '#a4d2ac';
							var point_color = '#7bad56';
						}
						$('.table-matrix').find('tr').eq({{$point_one[0]}}).find('td').eq({{$point_one[1]}}).css('background-color',path_color);
						$('.table-matrix').find('tr').eq({{$point_two[0]}}).find('td').eq({{$point_two[1]}}).css('background-color',path_color);
					}
				}
			});
		});
	});
</script>