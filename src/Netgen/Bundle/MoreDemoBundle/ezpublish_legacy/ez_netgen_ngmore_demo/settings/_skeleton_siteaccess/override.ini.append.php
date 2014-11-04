<?php /* #?ini charset="utf-8"?

## UNIVERSAL BLOCK ITEM

[block_item]
Source=node/view/block_item.tpl
MatchFile=block_item/block_item.tpl
Subdir=templates

# DOWNLOAD LINK

[download_link]
Source=content/datatype/view/ezxmltags/link.tpl
MatchFile=download_link.tpl
Subdir=templates
Match[classification]=download_link

# NG INFOBOX

[full_ng_infobox]
Source=node/view/full.tpl
MatchFile=full/ng_infobox.tpl
Subdir=templates
Match[class_identifier]=ng_infobox

[line_ng_infobox]
Source=node/view/line.tpl
MatchFile=line/ng_infobox.tpl
Subdir=templates
Match[class_identifier]=ng_infobox

[embed_ng_infobox]
Source=content/view/embed.tpl
MatchFile=embed/ng_infobox.tpl
Subdir=templates
Match[class_identifier]=ng_infobox

# NG SHORTCUT

[line_ng_shortcut]
Source=node/view/line.tpl
MatchFile=line/ng_shortcut.tpl
Subdir=templates
Match[class_identifier]=ng_shortcut

[listitem_ng_shortcut]
Source=node/view/listitem.tpl
MatchFile=listitem/ng_shortcut.tpl
Subdir=templates
Match[class_identifier]=ng_shortcut

[embed_ng_shortcut]
Source=content/view/embed.tpl
MatchFile=embed/ng_shortcut.tpl
Subdir=templates
Match[class_identifier]=ng_shortcut

# NG VIDEO

[full_ng_video]
Source=node/view/full.tpl
MatchFile=full/ng_video.tpl
Subdir=templates
Match[class_identifier]=ng_video

[line_ng_video]
Source=node/view/line.tpl
MatchFile=line/ng_video.tpl
Subdir=templates
Match[class_identifier]=ng_video

[listitem_ng_video]
Source=node/view/listitem.tpl
MatchFile=listitem/ng_video.tpl
Subdir=templates
Match[class_identifier]=ng_video

[embed_ng_video]
Source=content/view/embed.tpl
MatchFile=embed/ng_video.tpl
Subdir=templates
Match[class_identifier]=ng_video

# NG VIDEO EXTERNAL

[full_ng_video_external]
Source=node/view/full.tpl
MatchFile=full/ng_video_external.tpl
Subdir=templates
Match[class_identifier]=ng_video_external

[line_ng_video_external]
Source=node/view/line.tpl
MatchFile=line/ng_video_external.tpl
Subdir=templates
Match[class_identifier]=ng_video_external

[listitem_ng_video_external]
Source=node/view/listitem.tpl
MatchFile=listitem/ng_video_external.tpl
Subdir=templates
Match[class_identifier]=ng_video_external

[embed_ng_video_external]
Source=content/view/embed.tpl
MatchFile=embed/ng_video_external.tpl
Subdir=templates
Match[class_identifier]=ng_video_external

# NG AUDIO

[full_ng_audio]
Source=node/view/full.tpl
MatchFile=full/ng_audio.tpl
Subdir=templates
Match[class_identifier]=ng_audio

[line_ng_audio]
Source=node/view/line.tpl
MatchFile=line/ng_audio.tpl
Subdir=templates
Match[class_identifier]=ng_audio

[embed_ng_audio]
Source=content/view/embed.tpl
MatchFile=embed/ng_audio.tpl
Subdir=templates
Match[class_identifier]=ng_audio

# NG GALLERY

[full_ng_gallery]
Source=node/view/full.tpl
MatchFile=full/ng_gallery.tpl
Subdir=templates
Match[class_identifier]=ng_gallery

[line_ng_gallery]
Source=node/view/line.tpl
MatchFile=line/ng_gallery.tpl
Subdir=templates
Match[class_identifier]=ng_gallery

[embed_ng_gallery]
Source=content/view/embed.tpl
MatchFile=embed/ng_gallery.tpl
Subdir=templates
Match[class_identifier]=ng_gallery

# NG BANNER

[embed_ng_banner]
Source=content/view/embed.tpl
MatchFile=embed/ng_banner.tpl
Subdir=templates
Match[class_identifier]=ng_banner

# NG BANNER VIDEO

[embed_ng_banner_video]
Source=content/view/embed.tpl
MatchFile=embed/ng_banner_video.tpl
Subdir=templates
Match[class_identifier]=ng_banner_video

# NG BLOG POST

[full_ng_blog_post]
Source=node/view/full.tpl
MatchFile=full/ng_blog_post.tpl
Subdir=templates
Match[class_identifier]=ng_blog_post

[line_ng_blog_post]
Source=node/view/line.tpl
MatchFile=line/ng_blog_post.tpl
Subdir=templates
Match[class_identifier]=ng_blog_post

[embed_ng_blog_post]
Source=content/view/embed.tpl
MatchFile=embed/ng_blog_post.tpl
Subdir=templates
Match[class_identifier]=ng_blog_post

# NG ARTICLE

[full_ng_article]
Source=node/view/full.tpl
MatchFile=full/ng_article.tpl
Subdir=templates
Match[class_identifier]=ng_article

[line_ng_article]
Source=node/view/line.tpl
MatchFile=line/ng_article.tpl
Subdir=templates
Match[class_identifier]=ng_article

[embed_ng_article]
Source=content/view/embed.tpl
MatchFile=embed/ng_article.tpl
Subdir=templates
Match[class_identifier]=ng_article

# NG CATEGORY

[full_ng_category]
Source=node/view/full.tpl
MatchFile=full/ng_category.tpl
Subdir=templates
Match[class_identifier]=ng_category

[line_ng_category]
Source=node/view/line.tpl
MatchFile=line/ng_category.tpl
Subdir=templates
Match[class_identifier]=ng_category

[embed_ng_category]
Source=content/view/embed.tpl
MatchFile=embed/ng_category.tpl
Subdir=templates
Match[class_identifier]=ng_category

# NG FRONTPAGE

[full_ng_frontpage]
Source=node/view/full.tpl
MatchFile=full/ng_frontpage.tpl
Subdir=templates
Match[class_identifier]=ng_frontpage

[line_ng_frontpage]
Source=node/view/line.tpl
MatchFile=line/ng_frontpage.tpl
Subdir=templates
Match[class_identifier]=ng_frontpage

[embed_ng_frontpage]
Source=content/view/embed.tpl
MatchFile=embed/ng_frontpage.tpl
Subdir=templates
Match[class_identifier]=ng_frontpage

# NG LANDING PAGE

[full_ng_landing_page]
Source=node/view/full.tpl
MatchFile=full/ng_landing_page.tpl
Subdir=templates
Match[class_identifier]=ng_landing_page

[line_ng_landing_page]
Source=node/view/line.tpl
MatchFile=line/ng_landing_page.tpl
Subdir=templates
Match[class_identifier]=ng_landing_page

[embed_ng_landing_page]
Source=content/view/embed.tpl
MatchFile=embed/ng_landing_page.tpl
Subdir=templates
Match[class_identifier]=ng_landing_page

# NG CATEGORY PAGE

[full_ng_category_page]
Source=node/view/full.tpl
MatchFile=full/ng_category_page.tpl
Subdir=templates
Match[class_identifier]=ng_category_page

[line_ng_category_page]
Source=node/view/line.tpl
MatchFile=line/ng_category_page.tpl
Subdir=templates
Match[class_identifier]=ng_category_page

[embed_ng_category_page]
Source=content/view/embed.tpl
MatchFile=embed/ng_category_page.tpl
Subdir=templates
Match[class_identifier]=ng_category_page

# NG HTMLBOX

[full_ng_htmlbox]
Source=node/view/full.tpl
MatchFile=full/ng_htmlbox.tpl
Subdir=templates
Match[class_identifier]=ng_htmlbox

[line_ng_htmlbox]
Source=node/view/line.tpl
MatchFile=line/ng_htmlbox.tpl
Subdir=templates
Match[class_identifier]=ng_htmlbox

[embed_ng_htmlbox]
Source=content/view/embed.tpl
MatchFile=embed/ng_htmlbox.tpl
Subdir=templates
Match[class_identifier]=ng_htmlbox

# NG FEEDBACK FORM

[full_ng_feedback_form]
Source=node/view/full.tpl
MatchFile=full/ng_feedback_form.tpl
Subdir=templates
Match[class_identifier]=ng_feedback_form

[line_ng_feedback_form]
Source=node/view/line.tpl
MatchFile=line/ng_feedback_form.tpl
Subdir=templates
Match[class_identifier]=ng_feedback_form

[embed_ng_feedback_form]
Source=content/view/embed.tpl
MatchFile=embed/ng_feedback_form.tpl
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

# NG NEWS

[full_ng_news]
Source=node/view/full.tpl
MatchFile=full/ng_news.tpl
Subdir=templates
Match[class_identifier]=ng_news

[line_ng_news]
Source=node/view/line.tpl
MatchFile=line/ng_news.tpl
Subdir=templates
Match[class_identifier]=ng_news

[listitem_ng_news]
Source=node/view/listitem.tpl
MatchFile=listitem/ng_news.tpl
Subdir=templates
Match[class_identifier]=ng_news

[embed_ng_news]
Source=content/view/embed.tpl
MatchFile=embed/ng_news.tpl
Subdir=templates
Match[class_identifier]=ng_news

## BLOCK

[block_page_title]
Source=block/view/view.tpl
MatchFile=block/page_title.tpl
Subdir=templates
Match[type]=PageTitle
Match[view]=default

[block_content_grid]
Source=block/view/view.tpl
MatchFile=block/content_grid.tpl
Subdir=templates
Match[type]=ContentGrid

[block_content_grid_dynamic]
Source=block/view/view.tpl
MatchFile=block/content_grid_dynamic.tpl
Subdir=templates
Match[type]=ContentGridDynamic

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
