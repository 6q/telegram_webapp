	@foreach ($templates as $template)
		<tr {!! !$template->status? 'class="warning"' : '' !!}>
			<td class="text-primary"><strong>{{ $template->title }}</strong></td>
			
			<td>{{ $template->title }}</td>
			<td>{{ $template->subject }}</td>
			<td>
			@if($template->status ==1)
			 <img class="img-responsive" src="img/enable.png" alt="">
			@else
			 <img class="img-responsive" src="img/disable.png" alt="">
			@endif
			</td>
			<td>{!! link_to_route('emailtemplate.show', trans('back/emailtemplate.see'), [$template->id], ['class' => 'btn btn-success btn-block btn']) !!}</td>
			<td>{!! link_to_route('emailtemplate.edit', trans('back/emailtemplate.edit'), [$template->id], ['class' => 'btn btn-warning btn-block']) !!}</td>
			<td>
				{!! Form::open(['method' => 'DELETE', 'route' => ['emailtemplate.destroy', $template->id]]) !!}
				{!! Form::destroy(trans('back/emailtemplate.destroy'), trans('back/emailtemplate.destroy-warning')) !!}
				{!! Form::close() !!}
			</td>
		</tr>
	@endforeach