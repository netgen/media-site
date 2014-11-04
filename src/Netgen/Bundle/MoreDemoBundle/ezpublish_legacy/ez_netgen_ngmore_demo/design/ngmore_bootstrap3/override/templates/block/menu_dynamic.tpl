{if and($block.custom_attributes.root_node|ne(''), $block.custom_attributes.root_node|gt(0))}

    {def
        $parent_node = fetch( 'content', 'node', hash( 'node_id', $block.custom_attributes.root_node ) )
        $current_node = fetch('content', 'node', hash('node_id', $current_node_id))
        $limit = 1000
        $fetch_array = array()
        $sort_array = array()
        $order_direction = true()
        $depth = 1
        $fetch_entire_subtree = false()
    }

    {if $block.custom_attributes.advanced_order_direction|eq('desc')}
        {set $order_direction = false()}
    {/if}
    {switch match=$block.custom_attributes.advanced_order}
        {case match='parent_node_sort_array'}
            {set $sort_array = $parent_node.sort_array}
        {/case}
        {case match='attribute'}
            {set $sort_array = array( 'attribute', $order_direction, $block.custom_attributes.advanced_custom_order )}
        {/case}
        {case}
            {set $sort_array = array( $block.custom_attributes.advanced_order, $order_direction )}
        {/case}
    {/switch}

    {if $block.custom_attributes.advanced_class_filter_array|ne('')}
        {set $fetch_array = $fetch_array|merge( hash('class_filter_type', $block.custom_attributes.advanced_class_filter_type))}
        {set $fetch_array = $fetch_array|merge( hash('class_filter_array', $block.custom_attributes.advanced_class_filter_array|explode(',')))}
    {else}
        {set $fetch_array = $fetch_array|merge( hash('class_filter_type', 'include'))}
        {set $fetch_array = $fetch_array|merge( hash('class_filter_array', ezini('MenuSettings', 'DefaultMenuIdentifierList', 'ngmore.ini')))}
    {/if}

    {if $block.custom_attributes.advanced_limit|ne('')}
        {set $fetch_array = $fetch_array|merge( hash('limit', $block.custom_attributes.advanced_limit))}
    {else}
        {set $fetch_array = $fetch_array|merge( hash('limit', $limit))}
    {/if}

    {if and($block.custom_attributes.depth|ne(''), $block.custom_attributes.depth|is_numeric(), $block.custom_attributes.depth|gt(0))}
        {set $depth = $block.custom_attributes.depth}
    {/if}

    {if $block.custom_attributes.advanced_fetch_entire_subtree|eq(1)}
        {set $fetch_entire_subtree = true()}
    {/if}

    {def $valid_nodes = fetch('content', 'list', $fetch_array|merge(hash('parent_node_id', $block.custom_attributes.root_node))|merge(hash('sort_by', $sort_array)) )}

    {if $valid_nodes|count}
        <div {if and( is_set( $block.custom_attributes.advanced_html_id ), $block.custom_attributes.advanced_html_id|count_chars )} id="{$block.custom_attributes.advanced_html_id|wash}"{/if}
            class="block-type-menu-dynamic block-view-{$block.view|wash} {if and( is_set( $block.custom_attributes.advanced_html_class ), $block.custom_attributes.advanced_html_class|count_chars )} {$block.custom_attributes.advanced_html_class|wash}{/if} clearfix">

            {if ne( $block.name, '' )}
                <h2 class="block-title">{if $block.custom_attributes.advanced_link_to_root|eq(1)}<a href={$parent_node.url_alias|ezurl()}>{/if}{$block.name|wash()}{if $block.custom_attributes.advanced_link_to_root|eq(1)}</a>{/if}</h2>
            {/if}

            <ul>
                {foreach $valid_nodes as $node}
                    {include
                        uri=concat('design:parts/submenu_part.tpl')
                        root_node=$node
                        current_node=$current_node
                        depth=$depth

                        fetch_array=$fetch_array
                        use_root_sort_array = $block.custom_attributes.advanced_order|eq('parent_node_sort_array')
                        sort_array=$sort_array
                        fetch_entire_subtree = $fetch_entire_subtree
                    }
                {/foreach}
            </ul>
        </div>
    {/if}

    {undef $valid_nodes}

{/if}
