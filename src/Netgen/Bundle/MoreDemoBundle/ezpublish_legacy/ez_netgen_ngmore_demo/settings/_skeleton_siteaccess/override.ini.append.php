<?php /* #?ini charset="utf-8"?

# NG FEEDBACK FORM

[full_ng_feedback_form]
Source=node/view/full.tpl
MatchFile=full/ng_feedback_form.tpl
Subdir=templates
Match[class_identifier]=ng_feedback_form

[ng_feedback_form_collectedinfomail]
Source=content/collectedinfomail/form.tpl
MatchFile=collectedinfomail/ng_feedback_form.tpl
Subdir=templates
Match[class_identifier]=ng_feedback_form

[ng_feedback_form_collectedinfo]
Source=content/collectedinfo/form.tpl
MatchFile=collectedinfo/ng_feedback_form.tpl
Subdir=templates
Match[class_identifier]=ng_feedback_form

# BLOCK

[block_menu_dynamic]
Source=block/view/view.tpl
MatchFile=block/menu_dynamic.tpl
Subdir=templates
Match[type]=MenuDynamic

[block_tags_cloud]
Source=block/view/view.tpl
MatchFile=block/tags_cloud.tpl
Subdir=templates
Match[type]=TagsCloud

# EZXML TAGS

[factbox]
Source=content/datatype/view/ezxmltags/factbox.tpl
MatchFile=datatype/ezxmltext/factbox.tpl
Subdir=templates

[quote]
Source=content/datatype/view/ezxmltags/quote.tpl
MatchFile=datatype/ezxmltext/quote.tpl
Subdir=templates

[table_cols]
Source=content/datatype/view/ezxmltags/table.tpl
MatchFile=datatype/ezxmltext/table_cols.tpl
Subdir=templates
Match[classification]=cols

[table_comparison]
Source=content/datatype/view/ezxmltags/table.tpl
MatchFile=datatype/ezxmltext/table_comparison.tpl
Subdir=templates
Match[classification]=comparison

## DEFAULT OVERRIDE FOR CLASSES WITHOUT FULL TEMPLATES

[not_overriden_content]
Source=content/view/full.tpl
MatchFile=not_overriden.tpl
Subdir=templates

[not_overriden_node]
Source=node/view/full.tpl
MatchFile=not_overriden.tpl
Subdir=templates
*/ ?>
