@if ($paginator->getLastPage() > 1)
<?php $previousPage = ($paginator->getCurrentPage() > 1) ? $paginator->getCurrentPage() - 1 : 1; ?>  
<div class="panag">
	<ul>
		<li class="prev_page_btn"><a href="{{ $paginator->getUrl($previousPage) }}"><span style="opacity: 0">1</span></a></li>		
		@for ($i = $paginator->getCurrentPage() - 2 ; $i <= $paginator->getCurrentPage() +2 ; $i++)
		@if (($i >= 1) and ($i <= $paginator->getLastPage()))
		<li>
			<a href="{{ $paginator->getUrl($i) }}" class="page_link {{ ($paginator->getCurrentPage() == $i) ? ' cur_page' : '' }}">{{ $i }}</a>			
		</li>
		@endif
		@endfor		
		<li class="next_page_btn"><a href="{{ $paginator->getUrl($paginator->getCurrentPage()+1) }}"><span style="opacity: 0">1</span></a></li>
		<li><a href="javascript://" onclick="location.href='?page='+$('.goto_xpage').val()" class="goto_link">{{ Lang::get('title.go_to'); }}</a></li>
		<li><input onkeypress="if(event.keyCode==13) location.href='?page='+$('.goto_xpage').val()" type="text" class="goto_xpage" value="" maxlength="3"></li>
		<li><a href="{{ $paginator->getUrl($previousPage) }}" class="prev_page_link">{{ Lang::get('title.previous_page'); }}</a></li>
		<li><a href="{{ $paginator->getUrl($paginator->getCurrentPage()+1) }}" class="next_page_link">{{ Lang::get('title.next_page'); }}</a></li>
	</ul>
</div>
@endif