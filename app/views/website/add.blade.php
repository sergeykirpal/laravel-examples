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

	<div class="wrapper">
		<div class="form_title">
			<i></i><h2>{{ Lang::get('title.website_details'); }}</h2>
		</div>
		<form method="post"  enctype="multipart/form-data" id="formm">
		<fieldset id="website_add_form">			
			<span class="form_row">
				<label for="website_name" class="label_form">{{ Lang::get('title.website_name'); }}</label><input type="text" name="website_name" required>
			</span>
			<span class="form_row">
				<label for="website_url" class="label_form">{{ Lang::get('title.website_url'); }}</label><input placeholder="http://" type="text" name="website_url" required>
			</span>
			<?php
			/*
			<span class="form_row">
				<label for="website_url" class="label_form">Website language</label><input placeholder="" type="text" name="website_lang" required>
			</span>
			<span class="form_row">
				<label for="website_url" class="label_form">Website country</label><input placeholder="" type="text" name="website_countrys" required>
			</span>			 
			 */
			?>
			
			<span class="form_row">
				<label for="website_kw" class="label_form">{{ Lang::get('title.category'); }}</label>
				<select name="website_category" class="label_select" required>
					<option></option>
					@if(isset($categories) && count($categories))
                    @foreach($categories as $cat)
					<option value="{{$cat->id}}">{{ $cat->name }}</option>					
					@endforeach
					@endif
				</select>				
			</span>
			<span class="form_row">
				<label for="website_kw" class="label_form">{{ Lang::get('title.keywords'); }}</label><input type="text" name="website_kw" required>
			</span>
			<span class="form_row">
				<label for="website_desc" class="label_form">{{ Lang::get('title.description'); }}</label>
				<textarea name="website_desc" style="width: 100%" required></textarea>
			</span>
			
			    @if(isset($fields) && count($fields))                                      
                    @foreach($fields as $field)
                        {{ Websitefield::generateField($field); }}
                    @endforeach                        
               @endif
			
				
				<button type="submit" class="save"><i></i>{{ Lang::get('title.save'); }}</button>					
		</fieldset>
		</form>
	</div>

	<div class="notice">
		<div class="n_top">
			<i class="n_error_icon"></i><div class="n_error_text">{{ Lang::get('title.website_added'); }}</div><i class="n_close"></i>
		</div>
		<div class="n_content">
			{{ Lang::get('title.want_add'); }}
		</div>
		<div class="n_bottom">
			<span>{{ Lang::get('title.click'); }}</span><a href="#">{{ Lang::get('title.click'); }}</a>
		</div>
	</div>	
</section>		
<script>
$( "#formm" ).validate({
  rules: {
    email: {
      required: true,
      email: true
    },
    website_url: {
      required: true,
      url: true,
      remote: "/website/check"
    },
    number: {
      required: true,
      number: true
    }
  },
  messages: {    
    website_url: {      
      remote: "{{ Lang::get('title.already_added'); }}"
    }
  }
});
</script>
@stop 
@section('right-column')
<aside id="right-column">
	@include('member.template.addnew')
	@include('member.template.banners') 
</aside>
@stop  