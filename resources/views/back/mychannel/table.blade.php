@if($totalChannels > 0)
	@foreach ($channels as $channel)
		<tr>
			<td>{{ $channel->name }}</td>
			<!--<td>
				@if(!empty($bot->bot_image))
					{!! HTML::image('uploads/'.$bot->bot_image,'',array('class' => 'thumb')) !!}
				@endif
			</td>
			-->
			<td>{{ $channel->created_at }}</td>
			<td>
				<a class="btn btn-success btn-block btn" href="{!! URL::to('my_channel/mychannel_detail/'.$channel->id) !!}">{!! trans('back/mychannel.mychannel_detail') !!}</a>
			</td>
            <td>
				{!! Form::open(['method' => 'DELETE', 'route' => ['my_channel.destroy', $channel->id]]) !!}
				{!! Form::destroy(trans('back/users.destroy'), trans('back/users.destroy-warning')) !!}
				{!! Form::close() !!}
			</td>
		</tr>
	@endforeach
@else
	<tr>
		<td colspan="11" class="no_record">{!! trans('back/mychannel.no_record') !!}</td>
	</tr>
@endif