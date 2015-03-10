<div style="border-bottom: solid 1px #ccc;padding:  0 0 10px 0">
<div class="comment-parent">
	
	<div class="comment-date">		
		{{$date_f}}
	</div>
	<div class="comment-content">
		@if (Auth::check() and $user->id==$id_user)
		<span class="comment-remove" onclick="removeComment($(this), {{$id}})">✕</span>
		@endif
		<div class="comment_user_image">
		@if ($photo)
			<img src="/downloads/photo/{{$photo}}" alt="{{$firstname}} {{$lastname}}" title="{{$firstname}} {{$lastname}}">
		@else
			<img src="/images/no-user-image-200.png">
		@endif	
		</div>
		<div class="comment_text">
			<span class="comment_user_name">{{ Lang::get('title.comment_by'); }} {{$firstname}} {{$lastname}}:</span>
			<p>
				{{e($comm)}}
			</p>
		</div>
		<div class="both"></div>				
	</div>	
</div>
<div class="both"></div>
	

@if(isset($comment) and count($comment->answers))    					 					 		                               
	@foreach($comment->answers as $answer)
		<?php $date_f_a = date('Y/m/d, H:i',strtotime($answer->date))?>
		<?php $comm_a = $answer->comm?>
		<?php $id_a = $answer->id?>
		<?php $id_site_a = $answer->id_site?>
		<?php $id_user_a = $answer->id_user?>
		<?php $photo_a = $answer->photo?>
    	<?php $firstname_a = $answer->firstname?>
    	<?php $lastname_a = $answer->lastname?>
		@include('website.comment.comment_answer')            	
	@endforeach					                    					        					 
@endif	
@if (Auth::check())
<div>
	<a href="javascript://" style="color:#de5b34" onclick="$(this).parent().find('.comm-reply').show('slow')">REPLY</a>	
	<div class="comm-reply" style="display: none">			
			<br>
			<textarea placeholder=" Reply..." class="comment-reply"></textarea>
			<button class="btn4" style="width: 120px;margin-left: 0;" onclick="sendAnswerComment($(this),{{$id_site}}, {{$id}})"><i></i>{{ Lang::get('title.submit'); }}</button>
	</div>
</div>	
@endif	
</div>