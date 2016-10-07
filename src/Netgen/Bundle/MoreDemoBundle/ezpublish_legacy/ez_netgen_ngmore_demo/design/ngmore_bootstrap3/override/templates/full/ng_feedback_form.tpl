{if $node.data_map.line_intro.has_content}
    {ezpagedata_set( 'description', $node.data_map.line_intro.content.output.output_text|strip_tags|shorten(152))}
{elseif $node.data_map.full_intro.has_content}
    {ezpagedata_set( 'description', $node.data_map.full_intro.content.output.output_text|strip_tags|shorten(152))}
{/if}

<div class="view-type view-type-{$viewmode|wash} ng-feedback-form clearfix">

    <h1 class="page-header">{attribute_view_gui attribute=$node.data_map.title}</h1>

    {if $node.data_map.full_intro.has_content}
        <div class="intro">
            {attribute_view_gui attribute=$node.data_map.full_intro}
        </div>
    {/if}

    {if $node.data_map.body.has_content}
        <div class="body">
            {attribute_view_gui attribute=$node.data_map.body}
        </div>
    {/if}

    {include name=Validation uri='design:content/collectedinfo_validation.tpl'
                            class='message-warning'
                            validation=$validation collection_attributes=$collection_attributes}

    <form method="post" action={"content/action"|ezurl}>
        <fieldset>
            <div class="form-group">
                <label>{$node.data_map.sender_name.contentclass_attribute.name}</label>
                <div class="sender-name">
                    {attribute_view_gui attribute=$node.data_map.sender_name}
                </div>
            </div>

            <div class="form-group">
                <label>{$node.data_map.email.contentclass_attribute.name}</label>
                <div class="email">
                    {attribute_view_gui attribute=$node.data_map.email}
                </div>
            </div>

            <div class="form-group">
                <label>{$node.data_map.subject.contentclass_attribute.name}</label>
                <div class="name">
                    {attribute_view_gui attribute=$node.data_map.subject}
                </div>
            </div>

            <div class="form-group">
                <label>{$node.data_map.message.contentclass_attribute.name}</label>
                <div class="message">
                    {attribute_view_gui attribute=$node.data_map.message}
                </div>
            </div>

            <input type="submit" class="btn btn-primary" name="ActionCollectInformation" value="{"Send form"|i18n("design/ngmore/full/feedback_form")}" />
            <input type="hidden" name="ContentNodeID" value="{$node.node_id}" />
            <input type="hidden" name="ContentObjectID" value="{$node.object.id}" />
            <input type="hidden" name="ViewMode" value="full" />
        </fieldset>
    </form>

</div>
