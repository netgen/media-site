<?php /* #?ini charset="utf-8"?

# DOWNLOAD LINK

[download_link]
Source=content/datatype/view/ezxmltags/link.tpl
MatchFile=download_link.tpl
Subdir=templates
Match[classification]=download_link

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

# EZ SYSTEMS DEFAULT OVERRIDES

## FILE

[line_file]
Source=node/view/line.tpl
MatchFile=line/file.tpl
Subdir=templates
Match[class_identifier]=file

[embed_file]
Source=content/view/embed.tpl
MatchFile=embed/file.tpl
Subdir=templates
Match[class_identifier]=file

[edit_file]
Source=content/edit.tpl
MatchFile=edit/file.tpl
Subdir=templates
Match[class_identifier]=file

## IMAGE

[line_image]
Source=node/view/line.tpl
MatchFile=line/image.tpl
Subdir=templates
Match[class_identifier]=image

[embed_image]
Source=content/view/embed.tpl
MatchFile=embed/image.tpl
Subdir=templates
Match[class_identifier]=image

[embed_inline_image]
Source=content/view/embed-inline.tpl
MatchFile=embed-inline/image.tpl
Subdir=templates
Match[class_identifier]=image

## LINK

[line_link]
Source=node/view/line.tpl
MatchFile=line/link.tpl
Subdir=templates
Match[class_identifier]=link

# DATATYPES

[ezgmaplocation_article]
Source=content/datatype/view/ezgmaplocation.tpl
MatchFile=datatype/view/ezgmaplocation_article.tpl
Subdir=templates
Match[class_identifier]=article

[ezstring_feedback_form]
Source=content/datatype/collect/ezstring.tpl
MatchFile=datatype/collect/ezstring_feedback_form.tpl
Subdir=templates
Match[class_identifier]=feedback_form

[ezemail_feedback_form]
Source=content/datatype/collect/ezemail.tpl
MatchFile=datatype/collect/ezemail_feedback_form.tpl
Subdir=templates
Match[class_identifier]=feedback_form

[eztext_feedback_form]
Source=content/datatype/collect/eztext.tpl
MatchFile=datatype/collect/eztext_feedback_form.tpl
Subdir=templates
Match[class_identifier]=feedback_form

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
