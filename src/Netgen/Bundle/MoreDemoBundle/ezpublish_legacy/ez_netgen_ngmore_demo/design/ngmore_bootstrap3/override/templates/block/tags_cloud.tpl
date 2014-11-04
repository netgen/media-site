{def $advanced_html_tag = 'h2'}
{if is_set( $block.custom_attributes.advanced_html_tag )}
    {set $advanced_html_tag = $block.custom_attributes.advanced_html_tag|wash}
{/if}

<div {if and( is_set( $block.custom_attributes.advanced_html_id ), $block.custom_attributes.advanced_html_id|count_chars )} id="{$block.custom_attributes.advanced_html_id|wash}"{/if}
    class="block-type-tags-cloud block-view-{$block.view|wash} {if and( is_set( $block.custom_attributes.advanced_html_class ), $block.custom_attributes.advanced_html_class|count_chars )} {$block.custom_attributes.advanced_html_class|wash}{/if} clearfix">

    {if is_set( $block.custom_attributes.parent_node )}
        {def $parent_node_id = $block.custom_attributes.parent_node
             $limit = cond( and( is_set( $block.custom_attributes.limit ), $block.custom_attributes.limit|ne('') ), $block.custom_attributes.limit, 10 )
             $offset = cond( and( is_set( $block.custom_attributes.advanced_offset ), $block.custom_attributes.advanced_offset|ne('') ), $block.custom_attributes.advanced_offset, 0 )
             $class_identifier = cond( and( is_set( $block.custom_attributes.advanced_class_identifier ), $block.custom_attributes.advanced_class_identifier|ne('') ), $block.custom_attributes.advanced_class_identifier, false() )
             $sort_by = false()
             $post_sort_by = cond( and( is_set( $block.custom_attributes.advanced_post_sort_by ), $block.custom_attributes.advanced_post_sort_by|ne('') ), $block.custom_attributes.advanced_post_sort_by, false() )
        }

        {if is_set( $block.custom_attributes.advanced_sort_by )}
            {switch match=$block.custom_attributes.advanced_sort_by}
                {case match='keyword'}
                    {set $sort_by = array( 'keyword', true() )}
                {/case}
                {case match='keyword_reverse'}
                    {set $sort_by = array( 'keyword', false() )}
                {/case}
                {case match='count'}
                    {set $sort_by = array( 'count', true() )}
                {/case}
                {case match='count_reverse'}
                    {set $sort_by = array( 'count', false() )}
                {/case}
            {/switch}
        {/if}

        {if ne( $block.name, '' )}
            <div class="attribute-header">
                <{$advanced_html_tag}>{$block.name|wash()}</{$advanced_html_tag}>
            </div>
        {/if}

        {eztagscloud( hash( 'parent_node_id', $parent_node_id,
                           'class_identifier', $class_identifier,
                           'offset' , $offset,
                           'limit', $limit,
                           'sort_by', $sort_by,
                           'post_sort_by', $post_sort_by))}
    {/if}

</div>
