@extends('member.template.page')

@section('content')
<section id="content">
	@include('member.template.search')			

        <ul class="breadcrumbs">
            <li>
                <a href="/">{{ Lang::get('title.index'); }}</a>
            </li>
            <li class="angle-right"></li>
            <li>{{ Lang::get('title.add_new_website'); }}</li>
        </ul>

        <div style="margin-top: 20px; font-size: 18px;">
            <p class="text11">{{ Lang::get('title.want_add_web'); }} <span class="text_member">{{ Lang::get('title.member'); }}</span> {{ Lang::get('title.or_as'); }} <span class="text_visitor">{{ Lang::get('title.visitor'); }}</span> ?</p><br>
            <button class="btn1" id="login_as_member"><a href="javascript://" >{{ Lang::get('title.member_b'); }}</a></button>
            <button onclick="location.href='/website/add'" class="btn1" style="background: #ff9d26; margin-left: 20px;">{{ Lang::get('title.visitor_b'); }}</button>
            <div class="both"></div>
        </div>
</section>		
@stop 
@section('right-column')
<aside id="right-column">
	@include('member.template.addnew')
	@include('member.template.banners') 
</aside>
@stop  