@extends('member.template.page')

@section('content')
{{ HTML::script('js/site_rating.js') }}
<section id="content">
	@include('member.template.search')	
				<ul class="breadcrumbs">
					<li>
						<a href="/">{{ Lang::get('title.index'); }}</a>
					</li>
					<li class="angle-right"></li>
					<li>{{ Lang::get('title.website_rating'); }}</li>
				</ul>
								
				<?php if (isset($_GET['vote']) and $_GET['vote']=='confirm'):?>					
				<div class="no-res">
					<span>{{ Lang::get('title.voteconfirm'); }}</span>									
				</div>				
				<?php endif;?>
				<?php if (isset($_GET['vote']) and $_GET['vote']=='ok'):?>					
				<div class="no-res">
					<span>{{ Lang::get('title.voteok'); }}</span>									
				</div>				
				<?php endif;?>								

				@include('website.head')	

				@if( isset($questions[0]) and !empty($questions[0]->raiting))
				<div class="form_title2">
					<i></i>
					<h2>{{ Lang::get('title.website_char'); }}</h2>	
				</div>

				<div class="wrapper" style="background: #efefe5; border: 0; padding-top: 10px; border-radius: 0;">
					      
												      					                                            
                    		@foreach($questions as $key=>$question)
                    		@if($question->raiting)                    		 
                    			@include('website.view_list')
                    		@endif						
                    		@endforeach
                    			                        
               			 								
			    </div>
			    @endif		
				
				<div class="form_title2">
					<i></i>
					<h2>{{ Lang::get('title.comments'); }}</h2>	
				</div>
				
				<div class="wrapper" style="background: #efefe5; border: 0; border-radius: 0;">
					
					@if(Auth::check())
					<br>
					<div class="wrapper" style="background: #fff;width: 760px;margin: 10px auto;">
						<div class="form_title">
							<i style="background-position: -73px -1px;"></i><h2>{{ Lang::get('title.new_comment'); }}</h2>
						</div>
						<div class="comment-text">
							<textarea name="new_comment" class="new_comment" placeholder="Comment..."></textarea>
							<button onclick="sendComment($(this), {{$website->id}})" class="btn4" style="width: 720px"><i></i>{{ Lang::get('title.submit'); }}</button>
						</div>
					</div>
					@else 
					<div class="wrapper" style="min-height: 20px;padding: 15px;">
					<div class="site_pr_desc" style="width: 100%;float: none">
					<p>
					{{ Lang::get('title.leave_comment'); }} <a id="login_as_member" href="javascript://">{{ Lang::get('title.login_s'); }}</a> {{ Lang::get('title.or'); }} <a id="reg_as_member" href="javascript://">{{ Lang::get('title.register_s'); }}</a>
					</p>
					</div>
					</div>
					@endif
				
				
					<div id="comments">
					
					@if(isset($paginator) && count($paginator))    					 					 		                               
					        @foreach($paginator as $comment)
					        	<?php $date_f = date('Y/m/d, H:i',strtotime($comment->date))?>
					        	<?php $comm = $comment->comm?>
					        	<?php $id = $comment->id?>
					        	<?php $id_site = $comment->id_site?>
					        	<?php $id_user = $comment->id_user?>
					        	<?php $photo = $comment->photo?>
					        	<?php $firstname = $comment->firstname?>
					        	<?php $lastname = $comment->lastname?>
					            @include('website.comment.comment')            	
					        @endforeach					                    					        					 
					 @endif
					
					</div>	
					@if(isset($paginator) && count($paginator))  
					@include('website.paginate')	
					@endif																	

					
				</div>
				
				
				<div class="iframe_options">
					<p class="text11">{{ Lang::get('title.want_gen_rat'); }}.</p>

					<h4>{{ Lang::get('title.language'); }}</h4>
					<ul>
						
					    <li>
					        <input id="i_lang_en" class="radio3" type="radio" name="lang" hidden="" value="en" checked="">
					        <label for="i_lang_en">{{ Lang::get('title.english'); }}</label>
					    </li>
					    <li>
					        <input id="i_lang_ru" class="radio3" type="radio" name="lang" hidden="" value="ru">
					        <label for="i_lang_ru">{{ Lang::get('title.russian'); }}</label>
					    </li>					    
					</ul>

					<h4>{{ Lang::get('title.dimensions'); }}</h4>
					
					<ul class="i_dimens_params">
						<li>{{ Lang::get('title.width'); }}</li>
						<li><input type="text" name="iframe_width" id="iframe_width" value="800"></li>
						<li>{{ Lang::get('title.height'); }}</li>
						<li><input type="text" name="iframe_height" id="iframe_height" value="50"> px</li>
					</ul>

					<h4>{{ Lang::get('title.border'); }}</h4>
					<ul>
						<li style="display: inline-block">
					        <input id="I_brdr_yes" class="radio3" type="radio" value="1" name="iframe_border" checked="" hidden="">
					        <label for="I_brdr_yes">{{ Lang::get('title.yes'); }}</label>
					    </li>
					    <li style="display: inline-block">
					        <input id="i_brdr_no" class="radio3" type="radio" value="0" name="iframe_border" hidden="">
					        <label for="i_brdr_no">{{ Lang::get('title.no'); }}</label>
					    </li>
					</ul>

					<h4>{{ Lang::get('title.colours'); }}</h4>
						<table class="table3">
							<tbody><tr>
								<td style="width: 110px">{{ Lang::get('title.background'); }}</td>
								<td>
									<div class="colorSelector" id="colSelBg">
										<div id="background" style="background-color:#fff"></div>
									</div>
								</td>
								<td class="cp_bg">#fff</td>
							</tr>
							<tr>
								<td style="width: 110px">{{ Lang::get('title.website_title'); }}</td>
								<td>
									<div class="colorSelector" id="colSelWt">
										<div id="WebsiteTitle" style="background-color:#000"></div>
									</div>
								</td>
								<td class="cp_wt">#000</td>
							</tr>
							<tr>
								<td style="width: 110px">{{ Lang::get('title.rating_graphs'); }}</td>
								<td>
									<div class="colorSelector" id="colSelRg">
										<div id="RatingGraphs" style="background-color:#de5b34"></div>
									</div>
								</td>
								<td class="cp_rg">#de5b34</td>
							</tr>
							<tr>
								<td style="width: 110px">{{ Lang::get('title.rating_title'); }}</td>
								<td>
									<div class="colorSelector" id="colSelRt">
										<div id="RatingTitle" style="background-color:#fff"></div>
									</div>
								</td>
								<td class="cp_rt">#fff</td>
							</tr>
						</tbody></table>

					<p class="text11">
                                            {{ Lang::get('title.want_rating'); }}
					</p>
					<div class="both"></div>
					<br>
					<div class="iframe">
						
					</div>
					<button class="btn3 left prvr" onclick="previerRating()">{{ Lang::get('title.preview_rating'); }}</button>
					
					
					
					<textarea class="iframecode" disabled="" style="height: 100px"></textarea>
					<div class="both"></div>
					<button class="btn3 left copycode" onclick="copy()">{{ Lang::get('title.copy_code'); }}</button>
					
				</div>									
</section>
<script>
	previerRating();

	function previerRating(obj){		
		
		var iframe_height = $('#iframe_height').val();
		var iframe_width = $('#iframe_width').val();
		
		
		var background =  $('#background').css('background-color');
		
		
		var get = '?';
		var WebsiteTitle =  $('#WebsiteTitle').css('background-color');
		get += 'WebsiteTitle='+WebsiteTitle;
		var RatingGraphs =  $('#RatingGraphs').css('background-color');
		get += '&RatingGraphs='+RatingGraphs;
		var RatingTitle =  $('#RatingTitle').css('background-color');
		get += '&RatingTitle='+RatingTitle;
		var iframe_lang = $('input[name=lang]:checked').val();
		get += '&lang='+iframe_lang;

		
		var iframe_border = $('input[name=iframe_border]:checked').val();
		var border = 'border: solid 1px #ccc;';
		if (iframe_border!=1){
			border = 'border:none';
		}
		
		var code = '<iframe src="http://<?php echo $_SERVER['HTTP_HOST']?>/iframe/{{$website->id}}'+get+'" style="background-color: '+background+';width:'+iframe_width+'px; height:'+iframe_height+'px;'+border+'"></iframe>';
				
		$('.iframe').html(code);
		$('.iframecode').val(code);
	}
	function copy(){
		$('.iframecode').removeAttr('disabled').css('background','#fff');	
	}
	
	
	
</script>		
@stop 
@section('right-column')
<aside id="right-column">
	@include('member.template.addnew')
	@include('member.template.banners') 
</aside>
@stop  				