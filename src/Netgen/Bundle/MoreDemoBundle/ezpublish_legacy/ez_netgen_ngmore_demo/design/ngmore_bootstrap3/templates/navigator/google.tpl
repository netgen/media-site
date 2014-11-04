{default page_uri_suffix=false()
         left_max=2
         right_max=2}
{default name=ViewParameter
         page_uri_suffix=false()
         left_max=$left_max
         right_max=$right_max}

{let page_count=int( ceil( div( $item_count,$item_limit ) ) )
      current_page=min($:page_count,
                       int( ceil( div( first_set( $view_parameters.offset, 0 ),
                                       $item_limit ) ) ) )
      item_previous=sub( mul( $:current_page, $item_limit ),
                         $item_limit )
      item_next=sum( mul( $:current_page, $item_limit ),
                     $item_limit )

      left_length=min($ViewParameter:current_page,$:left_max)
      right_length=max(min(sub($ViewParameter:page_count,$ViewParameter:current_page,1),$:right_max),0)
      view_parameter_text=""
      offset_text=eq( ezini( 'ControlSettings', 'AllowUserVariables', 'template.ini' ), 'true' )|choose( '/offset/', '/(offset)/' )}
{* Create view parameter text with the exception of offset *}
{section loop=$view_parameters}
 {section-exclude match=$:key|eq('offset')}
 {section-exclude match=$:item|eq('')}
 {set view_parameter_text=concat($:view_parameter_text,'/(',$:key,')/',$:item)}
{/section}


{section show=$:page_count|gt(1)}

<div class="text-center">
  <ul class="pagination">

     {switch match=$:item_previous|lt(0) }
       {case match=0}
      <li><a rel="prev" href={concat($page_uri,$:item_previous|gt(0)|choose('',concat($:offset_text,$:item_previous)),$:view_parameter_text,$page_uri_suffix)|ezurl}>&laquo;&nbsp;</a></li>
       {/case}
       {case match=1}
       {/case}
     {/switch}

{if $:current_page|gt($:left_max)}
<li><a href={concat($page_uri,$:view_parameter_text,$page_uri_suffix)|ezurl}>1</a></li>
{if sub($:current_page,$:left_length)|gt(1)}
<li class="disabled"><a href="#">...</a></li>
{/if}
{/if}

    {section loop=$:left_length}
        {let page_offset=sum(sub($ViewParameter:current_page,$ViewParameter:left_length),$:index)}
          <li><a href={concat($page_uri,$:page_offset|gt(0)|choose('',concat($:offset_text,mul($:page_offset,$item_limit))),$ViewParameter:view_parameter_text,$page_uri_suffix)|ezurl}>{$:page_offset|inc}</a></li>
        {/let}
    {/section}

        <li class="active"><a href="#">{$:current_page|inc}</a></li>

    {section loop=$:right_length}
        {let page_offset=sum($ViewParameter:current_page,1,$:index)}
          <li><a href={concat($page_uri,$:page_offset|gt(0)|choose('',concat($:offset_text,mul($:page_offset,$item_limit))),$ViewParameter:view_parameter_text,$page_uri_suffix)|ezurl}>{$:page_offset|inc}</a></li>
        {/let}
    {/section}

{if $:page_count|gt(sum($:current_page,$:right_max,1))}
{if sum($:current_page,$:right_max,2)|lt($:page_count)}
<li class="disabled"><a href="#">...</a></li>
{/if}
<li><a href={concat($page_uri,$:page_count|dec|gt(0)|choose('',concat($:offset_text,mul($:page_count|dec,$item_limit))),$:view_parameter_text,$page_uri_suffix)|ezurl}>{$:page_count}</a></li>
{/if}

    {switch match=$:item_next|lt($item_count)}
      {case match=1}
        <li><a rel="next" href={concat($page_uri,$:offset_text,$:item_next,$:view_parameter_text,$page_uri_suffix)|ezurl}>&nbsp;&raquo;</a></li>
      {/case}
      {case}
      {/case}
    {/switch}
</ul>
</div>

{/section}

 {/let}
{/default}
{/default}
