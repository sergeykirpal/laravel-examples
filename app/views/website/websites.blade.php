@extends('member.template.page')

@section('content')
<section id="content">
	@include('member.template.search')	
	
<ul class="breadcrumbs">
	<li>
		<a href="/" title="Index">{{ Lang::get('title.index'); }}</a>
	</li>
	<li class="angle-right"></li>
	<li>
		<a href="/websites" title="Websites Lists">{{ Lang::get('title.websites_lists'); }}</a>
	</li>
	@if ($title)
	<li class="angle-right"></li>
	<li>{{$title}}</li>
	@endif
</ul>

<div class="form_title2">
	<i></i>
	<h2>{{$title}}</h2>	
</div>

		
 @if(isset($paginator) && count($paginator))    
 <div class="wrapper" style="background: #f6f6f6; border: 0; padding-top: 35px;"> 
 		                                
        @foreach($paginator as $site)
            @include('website.list')            	
        @endforeach
                    
        @include('website.paginate')
 </div>      
 @else
 <div id="search-results" style="display: block;top:0;">
			<div class="no-results" style="display: block;padding: 20px">
				<span>{{ Lang::get('title.want_add_website'); }}</span>
				<span><a href="/website/addnew">{{ Lang::get('title.add'); }}</a>{{ Lang::get('title.new_website'); }}</span>
			</div>
</div>     		                                 
 @endif
	
	
</section>		
@stop 
@section('right-column')
<aside id="right-column">
	@include('member.template.addnew')
	@include('member.template.banners') 
</aside>
@stop  				