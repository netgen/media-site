{def $has_media = or(
    and( is_set( $node.data_map.line_image ), $node.data_map.line_image.has_content ),
    and( is_set( $node.data_map.image ), $node.data_map.image.has_content )
)}

<div class="content-view-line {$node.class_identifier|explode( '_' )|implode( '-' )}">

    <h2 class="att-title">
        <a href={$node.url_alias|ezurl}>
            {if and( is_set( $node.data_map.title ), $node.data_map.title.has_content )}
                {attribute_view_gui attribute=$node.data_map.title}
            {else}
                {$node.name|wash}
            {/if}
        </a>
    </h2>
    <div class="row">

        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-9{if $has_media} media{/if} add-line">
            {if and( is_set( $node.data_map.line_image ), $node.data_map.line_image.has_content )}
                {attribute_view_gui image_class='i320' link_class='att-image pull-left' href=$node.url_alias|ezurl attribute=$node.data_map.line_image}
            {elseif and( is_set( $node.data_map.image ), $node.data_map.image.has_content )}
                {attribute_view_gui image_class='i320' link_class='att-image pull-left' href=$node.url_alias|ezurl attribute=$node.data_map.image}
            {/if}

            <div class="att-line-intro">
                {if and( is_set( $node.data_map.line_intro ), $node.data_map.line_intro.has_content )}
                    {attribute_view_gui attribute=$node.data_map.line_intro}
                {elseif and( is_set( $node.data_map.full_intro ), $node.data_map.full_intro.has_content )}
                    {attribute_view_gui attribute=$node.data_map.full_intro}
                {elseif and( is_set( $node.data_map.body ), $node.data_map.body.has_content )}
                    {$node.data_map.body.content.output.output_text|strip_tags|shorten(210)}
                {/if}
            </div>

            <div class="date">Last updated: {$node.object.current.modified|l10n( shortdatetime )}</div>
        </div>

        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-3">
            <div class="additional-info">
                <div class="type">
                    <label>
                        Type:
                    </label>
                    {$node.class_name}
                </div>
                <div class="score progress">
                    <div class="bar" style="width:{$node.score_percent|wash}%">{$node.score_percent|wash}%</div>
                </div>
            </div>
        </div>

    </div>

</div>
