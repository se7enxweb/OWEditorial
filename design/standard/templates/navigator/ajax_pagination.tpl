{*
	Il est possible de paramétrer le nombre de cases de la pagination avec le paramètre elements_limit :
	
	{include name=navigator
             uri='design:navigator/google.tpl'
             page_uri=$node.url_alias
             item_count=$children_count
             view_parameters=$view_parameters
             item_limit=$page_limit
             elements_limit=12}
*}

{default page_uri=false()
	     page_uri_suffix=false()
	     item_count=false()
	     view_parameters=array()
	     item_limit=false()
	     elements_limit=12
	     first=false()
	     last=false()
	     next=true()
	     prev=true()
}
{def $pagination_offset = first_set($offset, $view_parameters.offset, 0)}
	{if $item_count|div($item_limit)|gt(1)}
	{*
		Calcul :
		 - du nombre total de pages
		 - du nombre de pages précédentes
		 - du nombre de pages suivantes
		 - de l'offset précédent
		 - de l'offset suivant
	*}
	{def $nb_pages = $item_count|div($item_limit)|ceil|int
		 $current_page = min( $nb_pages,
	                     	  sum( int( ceil( div( first_set( $pagination_offset, 0 ), $item_limit ) ) ), 1)
	                     	)
	     
		 $item_previous=sub( mul( $current_page|sub(1), $item_limit ),
	                         $item_limit )
	     $item_next=sum( mul( $current_page|sub(1), $item_limit ),
	                     $item_limit )
	     
	     $nb_previous=$current_page|sub(1)
	     $nb_next=$nb_pages|sub($current_page)
		 
	     $display_first = 0
	     $display_last = 0
	     $display_next = 0
	     $display_prev = 0
	     
	     $first_left = 1
	     $last_right = $nb_pages
	     
	     $view_parameters_text = ''
	     $offset_text=eq( ezini( 'ControlSettings', 'AllowUserVariables', 'template.ini' ), 'true' )|choose( '/offset/', '/(offset)/' )
	}
	{foreach $view_parameters as $key => $value}
		{if and( $key|ne('offset'), $value|ne('') )}
			{set view_parameter_text=concat($view_parameter_text,'/(',$key,')/',$value)}
		{/if}
	{/foreach}
	
	{* Affichage ou non des flèches *}
	{if $current_page|gt(1)}
		{if $first}
			{set $display_first = 1}
		{/if}
		{if $prev}
			{set $display_prev = 1}
		{/if}
	{/if}
	{if $current_page|lt($nb_pages)}
		{if $next}
			{set $display_next = 1}
		{/if}
		{if $last}
			{set $display_last = 1}
		{/if}
	{/if}
	
	{* Calcul des pages à masquer par manque de place *}
	{def $reste = $elements_limit|sub( sum( $display_first, $display_prev, $display_next, $display_last ) )
		 $temp_right = $reste|sub(2)
		 $temp_left = $nb_pages|sub( $reste|sub(3) )}
	{* Si trop de pages *}
	{if $reste|lt( $nb_pages )}
		{* Si la page est en début de pagination *}
		{if and( $nb_previous|sum(2)|le($reste), $current_page|lt($temp_right) )}
			{set $last_right = $temp_right}
		{* Ou si la page est en fin de pagination *}
		{elseif and( $nb_next|sum(2)|le($reste), $current_page|gt($temp_left) )}
			{set $first_left = $temp_left}
		{* Sinon on la met au milieu *}
		{else}
			{set $last_right = $current_page|sum( $reste|sub(4)|div(2)|ceil|int )}
			{set $first_left = $last_right|sub( $reste|sub(5) )}
		{/if}
	{/if}

	<div class="pagenavigator">
		<p>
			{* Affichage des flèches précédents *}
			{*if $display_first}
				<li><a href={concat($page_uri,$view_parameter_text,$page_uri_suffix)|ezurl}>⇤</a>
			{/if*}
			{if $display_prev}
				<span class="previous"><a class="ajax-pagination" data-offset="{$item_previous}" href={concat($page_uri,$item_previous|gt(0)|choose('',concat($offset_text,$item_previous)),$view_parameter_text,$page_uri_suffix)|ezurl}>&laquo; {"Previous"|i18n("design/admin/navigator")}&nbsp;</a></span>
			{/if}
			
			{* Affichage de la première partie abrégée si besoin *}
			{if $first_left|gt(1)}
				<a class="ajax-pagination" data-offset="0" href={concat($page_uri,$view_parameter_text,$page_uri_suffix)|ezurl}>1</a>
				<span class="text disabled">...</span>
			{/if}
			
			{* Affichage de la section contenant la page en cours *}
			{for $first_left to $last_right as $i}
				{if $i|eq($current_page)}
					<span class="current">{$i}</span>
				{else}
					<a class="ajax-pagination" data-offset="{mul($i|sub(1),$item_limit)}" href={concat($page_uri,$i|gt(1)|choose('',concat($offset_text,mul($i|sub(1),$item_limit))),$view_parameter_text,$page_uri_suffix)|ezurl}>{$i}</a>
				{/if}
			{/for}
			
			{* Affichage de la dernière partie abrégée si besoin *}
			{if $last_right|lt($nb_pages)}
				<span class="text disabled">...</span>
				<a class="ajax-pagination" data-offset="{mul($nb_pages|dec,$item_limit))}" href={concat($page_uri,$nb_pages|dec|gt(0)|choose('',concat($offset_text,mul($nb_pages|dec,$item_limit))),$view_parameter_text,$page_uri_suffix)|ezurl}>{$nb_pages}</a>
			{/if}
			
			{* Affichage des flèches suivants *}
			{if $display_next}
				<span class="next"><a class="ajax-pagination" data-offset="{$item_next}" href={concat($page_uri,$offset_text,$item_next,$view_parameter_text,$page_uri_suffix)|ezurl}>&nbsp;{"Next"|i18n("design/admin/navigator")} &raquo;</a></span>		        
			{/if}
			{*if $display_last}
				<a href={concat($page_uri,$nb_pages|dec|gt(0)|choose('',concat($offset_text,mul($nb_pages|dec,$item_limit))),$view_parameter_text,$page_uri_suffix)|ezurl}>⇥</a>
			{/if*}
		</p>
	</div>

{/if}	
{/default}

