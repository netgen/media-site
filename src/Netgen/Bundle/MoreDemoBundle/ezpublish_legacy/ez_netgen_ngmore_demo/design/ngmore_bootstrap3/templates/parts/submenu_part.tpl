{if $use_root_sort_array}
    {set $sort_array = $root_node.sort_array}
{/if}
{if is_set($url)|not}
    {def $url=""}
{/if}
<li>
    {if $root_node.class_identifier|eq('ng_shortcut')}
        {if $root_node.data_map.related_object.has_content}
            {if $root_node.data_map.related_object.content.main_node.node_id|ne($root_node.node_id)}{* dead loop *}
                {set $url=$root_node.data_map.related_object.content.main_node.url_alias|ezurl(no)}
            {/if}
        {elseif or($root_node.data_map.url.has_content, $root_node.data_map.url.content|trim()|count())}
            {set $url=$root_node.data_map.url.content}
        {/if}
    {else}
        {set $url=$root_node.url_alias|ezurl(no)}
    {/if}
    <a href="{$url}" {if $current_node.path_array|contains($root_node.node_id)}class="selected {if $root_node.node_id|eq($current_node.node_id)}current{/if}"{/if}>{$root_node.name|wash}</a>

    {set $depth = $depth|dec()}
    {if
        and(
            or(
                $fetch_entire_subtree|eq(1),
                $current_node.path_array|contains($root_node.node_id)
                ),
            $depth|gt(0)
            )
        }
        {def $sub_nodes = fetch('content', 'list', $fetch_array|merge( hash( 'parent_node_id', $root_node.node_id))|merge(hash( 'sort_by', $sort_array )) )}
        <ul>
            {foreach $sub_nodes as $subnode}
                {include
                        uri=concat('design:parts/submenu_part.tpl')
                        root_node=$subnode
                        current_node=$current_node
                        depth=$depth

                        fetch_array=$fetch_array
                        use_root_sort_array = $use_root_sort_array
                        sort_array=$sort_array

                        fetch_entire_subtree = $fetch_entire_subtree
                }
            {/foreach}
        </ul>
    {/if}
</li>
