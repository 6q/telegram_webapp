	@foreach ($users as $user)
		<tr {!! !$user->status? 'class="warning"' : '' !!}>
			<td class="text-primary"><strong>{{ $user->first_name }}</strong></td>
			
			<td>{{ $user->email }}</td>
			<td>{{ $user->role->title }}</td>
			<td>
			@if($user->status ==1)
			 <img class="img-responsive" src="img/enable.png" alt="">
			@else
			 <img class="img-responsive" src="img/disable.png" alt="">
			@endif
			</td>
			<td><a class="btn btn-success btn-block btn" href="{!! URL::to('my_channel/userchannel/'.$user->id) !!}">{!! trans('back/users.view_channel') !!}</a></td>
            <td><a class="btn btn-success btn-block btn" href="{!! URL::to('bot/userbot/'.$user->id) !!}">{!! trans('back/users.bot') !!}</a></td>
			<td>{!! link_to_route('user.show', trans('back/users.see'), [$user->id], ['class' => 'btn btn-success btn-block btn']) !!}</td>
			<td>{!! link_to_route('user.edit', trans('back/users.edit'), [$user->id], ['class' => 'btn btn-warning btn-block']) !!}</td>
			<td>
				{!! Form::open(['method' => 'DELETE', 'route' => ['user.destroy', $user->id]]) !!}
				{!! Form::destroy(trans('back/users.destroy'), trans('back/users.destroy-warning')) !!}
				{!! Form::close() !!}
			</td>
		</tr>
	@endforeach