<div id="links">
    {if and(is_set($relation_list), $relation_list|count()|gt(0))}
        {def
            $menu_items=array()
            $related_node_item_node=''
        }
        {foreach $relation_list as $related_node_item}
            {set $related_node_item_node=fetch('content', 'node', hash('node_id', $related_node_item.node_id))}{* fetchom provjeravamo postoji li jezicna verzija *}
            {if is_set($related_node_item_node.node_id)}
                {set $menu_items = $menu_items|append($related_node_item_node)}
                {if and($current_node_id|ne($root_node.node_id), is_set($current_node), $current_node.path_array|contains($related_node_item_node.node_id))}
                    {set $current_node_in_path = $related_node_item_node.node_id}
                {/if}
            {/if}
        {/foreach}
        {if $menu_items|count}

                <ul class="clearfix">
                    {foreach $menu_items as $index => $menu_node}
                        <li{if $index|eq( 0 )} class="first"{/if}><a href={$menu_node.url_alias|ezurl()}>{$menu_node.name}</a></li>
                    {/foreach}
                </ul>

        {/if}
    {/if}
</div>
