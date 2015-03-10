@extends('member.template.page')

@section('content')
<section id="content">
	@include('member.template.search')
	
	<div class="form_title2">	
		<h2>{{ Lang::get('title.confirmedrating'); }}</h2>	
	</div>
	<div class="wrapper" style="border: none;">
		<a style="color: #de5b34" href="/website/view/{{$website->id}}">{{ Lang::get('title.WebsiteRating'); }}</a>
		<p>
		{{$website->name}}	
		</p>
		<p>
		{{$website->url}}	
		</p>
		 
	</div>
		
</section>		
@stop 
@section('right-column')
<aside id="right-column">
	@include('member.template.addnew')
	@include('member.template.banners') 
</aside>
@stop  