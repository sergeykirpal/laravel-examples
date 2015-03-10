@extends('member.template.page')

@section('content')
<section id="content">
	@include('member.template.search')
	
	<div class="form_title2">	
		<h2>{{ Lang::get('title.success_added'); }}</h2>	
	</div>
		
</section>		
@stop 
@section('right-column')
<aside id="right-column">
	@include('member.template.addnew')
	@include('member.template.banners') 
</aside>
@stop  