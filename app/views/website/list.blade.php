<div class="row3">
		<span class="web_rate_position">{{$site->rating}}</span>
		<span class="web_rate_url">
			<a href="/website/rating/{{$site->id}}">{{parse_url($site->url, PHP_URL_HOST)}}</a>
		</span>
		<div class="web_wrate">
			<div class="web_wrating" style="width: {{$site->rating * 10}}%;"></div>
		</div>
		<span class="web_rate_p2">{{$site->rating}}</span>
		<a href="/website/view/{{$site->id}}" class="view_ratings">{{ Lang::get('title.view_ratings'); }}</a>
	</div>