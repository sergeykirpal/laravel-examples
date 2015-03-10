<div class="wrapper">
		<div class="site_pr_head">
			<h1>{{e($website->name)}}</h1>
			<div class="bView">
			@if(Route::currentRouteAction()=='WebsiteController@rating')
				<a href="/website/view/{{e($website->id)}}">View Rating</a>
			@endif
			@if(Route::currentRouteAction()=='WebsiteController@view')
				<a href="/website/rating/{{e($website->id)}}">Set Your rating</a>
			@endif
			</div>
		</div>
		<div class="both"></div>
		@if ($websiteImg['small'])
		<img class="site_pr_image" alt="{{e($website->name)}}" title="{{e($website->name)}}" src="{{$websiteImg['small']}}">
		@endif
		<div class="site_pr_desc">									    			    			    
				{{e($website->description)}} 
				<br>
				<p>
				<a href="#">{{ Lang::get('title.details'); }}</a>
				</p>
				<div class="details">
					<p>
						<b>Website Url:</b> {{e($website->url)}}						
					</p>
					<p>
						<b>Category:</b> {{e($website->catname)}}
					</p>					
					@foreach($websiteAdditional as $field)
            			<p>
            				@if ($field->type=='select_multiple')
            				<?php $value = json_decode($field->value,true); $value = implode(', ',$value) ?> 
            				<b>{{$field->label}}:</b> {{e($value)}}
            				@else
            				<b>{{$field->label}}:</b> {{e($field->value)}}
            				@endif
            			</p>
        			@endforeach
        			<div class="both"></div>
				</div>
				<div class="both"></div>
			
			
		</div>
		<div class="site_circ_rating">
			<canvas id="site_rating" width="165" height="165" data-completeness="{{$website->rating}}"></canvas> 
		</div>
		<div class="both"></div>
	</div>
	<div class="both"></div>