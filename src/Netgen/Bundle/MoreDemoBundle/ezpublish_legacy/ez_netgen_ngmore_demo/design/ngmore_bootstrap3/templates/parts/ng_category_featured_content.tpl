{def $featured_node_array = array()}
{foreach $node.data_map.featured_content.content.relation_list as $rel_item}
    {set $featured_node_array = $featured_node_array|append(fetch(content,node,hash(node_id, $rel_item.node_id)))}
{/foreach}
{if $featured_node_array|count()}
    <div class="att-featured-content">
        {foreach $featured_node_array as $related_node}
            {node_view_gui view="line" content_node=$related_node}
        {/foreach}
    </div>
{/if}
{undef $featured_node_array}
