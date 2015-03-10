<!DOCTYPE HTML>
<html lang="en-US">
<head>
    <meta charset="UTF-8">    
   	 <title>{{ Lang::get('title.estimate_websites'); }}</title>            
    {{ HTML::style('css/style.css') }}      
</head>
<body>
<div class="row3" style="margin: 0;width: 98%;background: none;">		
	<span class="web_rate_url"><a style="color: {{$WebsiteTitle}}" href="http://<?php echo $_SERVER['HTTP_HOST']?>/website/view/{{$website->id}}">{{e($website->name)}}</a></span>
	<div class="web_wrate">
		<div style="width: {{$website->rating * 10 }}%;background-color: {{$RatingGraphs}}" class="web_wrating"></div>
	</div>
	<span style="margin-top: 7px;padding: 0 5px;background-color: {{$RatingGraphs}};color: {{$RatingTitle}}">{{$website->rating}}</span>
</div>
</body>
</html>
