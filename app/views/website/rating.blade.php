@extends('member.template.page')

@section('content')
{{ HTML::script('js/site_rating.js') }}
<section id="content">
	@include('member.template.search')	
	<ul class="breadcrumbs">
		<li>
                   <a href="/"> {{ Lang::get('title.index'); }}</a>
		</li>
		<li class="angle-right"></li>
		<li>{{ Lang::get('title.website_rating'); }}</li>
	</ul>
	<?php if (isset($_GET['vote']) and $_GET['vote']=='done'):?>
		
	<div class="no-res" style="display: block">
		<span>{{ Lang::get('title.already_voted'); }}</span>
		<span><a href="/website/view/{{$id}}">{{ Lang::get('title.view_results'); }}</a></span>
	</div>
		
	<?php endif;?>


	@include('website.head')	
	

	<div class="form_title2">
		<i></i>
		<h2>{{ Lang::get('title.website_rating_b'); }}</h2>	
	</div>

	
	<div class="wrapper" style="background: #efefe5; border: 0; padding-top: 10px;">	
		
		<form method="post" id="formm">						
		       @if(isset($questions) && count($questions))                                      
                    @foreach($questions as $key=>$question)                    
                        <div class="row3">                        	
							<span class="web_rate_position">{{$key+1}}</span>
							<span class="web_rate_url">
								<a href="javascript://">{{$question->question}}</a>
							</span>
						</div>
						<div class="row3-content">	
						<?php $i=0;?>					
						 @foreach($answers as $k=>$answer)						 			
                                    @if($question->id == $answer->id_question)                                        
                                    <?php if ($i==0) $required = $question->required; else $required=0;?>                                	                                     
                                     {{ Answer::generateFieldForm($answer,$required,$question->id); }}
                                     <?php $i++?>    
                                    @else 
                                    <?php $i=0;?>	                                 
                                    @endif
                         @endforeach
                         </div>
                    @endforeach                        
               @endif
               
               @if(Auth::check()==false)
               <div class="row3-content">

				<input type="email" name="email" placeholder="{{ Lang::get('title.email'); }}" class="rec_to_shop" required="">
				{{ Lang::get('title.emailratingconfirm'); }}										
			   </div>               
               @endif
	
	<button type="submit" class="save_rating">{{ Lang::get('title.save_rating'); }}</button>
	<div class="both"></div>
	</form>
	</div>					
</section>	
<script>
jQuery.validator.addClassRules("number", {
   required: true,
  number: true,
  min: 0,
  max: 10
});
$( "#formm" ).validate();
</script>	
@stop 
@section('right-column')
<aside id="right-column">
	@include('member.template.addnew')
	@include('member.template.banners') 
</aside>
@stop  		