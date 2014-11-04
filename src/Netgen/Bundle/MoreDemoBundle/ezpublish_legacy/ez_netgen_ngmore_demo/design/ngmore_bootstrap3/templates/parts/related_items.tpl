{def $related_node_array = array() $related_node = false()}

{if is_set($view)|not()}
    {def $view = 'line'}
{/if}

{if is_set($relation_attribute)|not()}
    {def $relation_attribute="related_content"}
{/if}

{if is_set($enclosing_block_css_class)|not()}
    {def $enclosing_block_css_class = "att-related-content"}
{/if}

{foreach $node.data_map.[$relation_attribute].content.relation_list as $rel_item}
    {set $related_node = fetch(content,node,hash(node_id, $rel_item.node_id))}
    {if and($related_node.is_invisible|not(), $rel_item.in_trash|not())}
        {set $related_node_array = $related_node_array|append($related_node)}
    {/if}
{/foreach}

{if $related_node_array|count()}
    <div class="{$enclosing_block_css_class}">
        {foreach $related_node_array as $rel_node}
            {node_view_gui view=$view content_node=$rel_node}
        {/foreach}
    </div>
{/if}

{undef $related_node_array $related_node}