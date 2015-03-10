<div id="comment-children">
	<div class="comment-date">
		{{$date_f_a}}
	</div>
	<div class="comment-content">
		@if (Auth::check() and $user->id==$id_user_a)
		<span class="comment-remove" style="margin: 0 0 0 550px;" onclick="removeCommentAns($(this), {{$id_a}})">✕</span>
		@endif
		<div class="comment_user_image">
        @if ($photo_a)
			<img src="/downloads/photo/{{$photo_a}}" alt="{{$firstname_a}} {{$lastname_a}}" title="{{$firstname_a}} {{$lastname_a}}">
		@else
			<img src="/images/no-user-image-200.png">
		@endif	
		</div>
		<div class="comment_text">
			<span class="comment_user_name">ANSWER by {{$firstname_a}} {{$lastname_a}}:</span>
			<p>
				{{e($comm_a)}}
			</p>
		</div>
	</div>
</div>
<div class="both"></div>