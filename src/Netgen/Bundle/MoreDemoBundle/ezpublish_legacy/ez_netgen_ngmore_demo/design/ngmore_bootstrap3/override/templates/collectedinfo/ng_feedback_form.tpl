{*TODO: Default template, make it better *}
{def $collection = cond( $collection_id, fetch( content, collected_info_collection, hash( collection_id, $collection_id ) ),
                          fetch( content, collected_info_collection, hash( contentobject_id, $node.contentobject_id ) ) )}

{set-block scope=global variable=title}{'Form %formname'|i18n( 'design/ezdemo/collectedinfo/form', , hash( '%formname', $node.name|wash() ) )}{/set-block}

<div class="attribute-header">
    <h1>{$object.name|wash}</h1>
</div>

{if $object.data_map.success_text.has_content}
    {attribute_view_gui attribute=$object.data_map.success_text}
{else}
    <p>{'Thank you for your feedback.'|i18n( 'design/ezdemo/collectedinfo/form' )}</p>
{/if}

{if $error}

{if $error_existing_data}
<p>{'You have already submitted this form. The data you entered was:'|i18n('design/ezdemo/collectedinfo/form')}</p>
{/if}

{/if}

{*foreach $collection.attributes as $attribute}

<p><strong>{$attribute.contentclass_attribute_name|wash}:</strong> {attribute_result_gui view=info attribute=$attribute} </p>

{/foreach*}

<a href={$node.parent.url|ezurl}>{'Return to site'|i18n('design/ezdemo/collectedinfo/form')}</a>
