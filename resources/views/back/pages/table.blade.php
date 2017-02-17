	@foreach ($pages as $page)
		<tr {!! !$page->status? 'class="warning"' : '' !!}>
			<td class="text-primary"><strong>{{ $page->title }}</strong></td>
			
			<td>{{ $page->meta_title }}</td>
			<td>{{ $page->meta_description }}</td>
			<td>{{ $page->meta_keyword }}</td>
			<td>
			@if($page->status ==1)
			 <img class="img-responsive" src="img/enable.png" alt="">
			@else
			 <img class="img-responsive" src="img/disable.png" alt="">
			@endif
			</td>
			<td>{!! link_to_route('page.show', trans('back/page.see'), [$page->id], ['class' => 'btn btn-success btn-block btn']) !!}</td>
			<td>{!! link_to_route('page.edit', trans('back/page.edit'), [$page->id], ['class' => 'btn btn-warning btn-block']) !!}</td>
			<!--<td>
				{!! Form::open(['method' => 'DELETE', 'route' => ['page.destroy', $page->id]]) !!}
				{!! Form::destroy(trans('back/page.destroy'), trans('back/page.destroy-warning')) !!}
				{!! Form::close() !!}
			</td>-->
		</tr>
	@endforeach