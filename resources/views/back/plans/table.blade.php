	@foreach ($plans as $plan)
		<tr>
			<td class="text-primary"><strong>{{ $plan->name }}</strong></td>
			
			<td>{{ $plan->duration }}</td>
			<td>{{ $plan->price }}</td>
			<td>
			@if($plan->status ==1)
			 <img class="img-responsive" src="img/enable.png" alt="">
			@else
			 <img class="img-responsive" src="img/disable.png" alt="">
			@endif
			</td>
			<td>{!! link_to_route('plan.show', trans('back/plans.see'), [$plan->id], ['class' => 'btn btn-success btn-block btn']) !!}</td>
			<td>{!! link_to_route('plan.edit', trans('back/users.edit'), [$plan->id], ['class' => 'btn btn-warning btn-block']) !!}</td>
			<td>
				{!! Form::open(['method' => 'DELETE', 'route' => ['plan.destroy', $plan->id]]) !!}
				{!! Form::destroy(trans('back/plans.destroy'), trans('back/plans.destroy-warning')) !!}
				{!! Form::close() !!}
			</td>
		</tr>
	@endforeach