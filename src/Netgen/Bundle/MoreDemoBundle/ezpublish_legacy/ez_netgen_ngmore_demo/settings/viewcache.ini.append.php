<?php /* #?ini charset="utf-8"?

[ViewCacheSettings]
ClearRelationTypes[]=common
ClearRelationTypes[]=reverse_common
ClearRelationTypes[]=reverse_embedded
ClearRelationTypes[]=reverse_attribute
SmartCacheClear=enabled

[forum_reply]
DependentClassIdentifier[]=forum_topic
DependentClassIdentifier[]=forum
ClearCacheMethod[]=object
ClearCacheMethod[]=parent
ClearCacheMethod[]=relating
ClearCacheMethod[]=siblings

[forum_topic]
DependentClassIdentifier[]=forum
ClearCacheMethod[]=object
ClearCacheMethod[]=parent
ClearCacheMethod[]=relating
ClearCacheMethod[]=siblings

[folder]
DependentClassIdentifier[]=folder
ClearCacheMethod[]=object
ClearCacheMethod[]=parent
ClearCacheMethod[]=relating

[gallery]
DependentClassIdentifier[]=folder
ClearCacheMethod[]=object
ClearCacheMethod[]=parent
ClearCacheMethod[]=relating
ClearCacheMethod[]=children

[image]
DependentClassIdentifier[]=gallery
ClearCacheMethod[]=object
ClearCacheMethod[]=parent
ClearCacheMethod[]=relating
ClearCacheMethod[]=siblings

[event]
DependentClassIdentifier[]=event_calender
ClearCacheMethod[]=object
ClearCacheMethod[]=parent
ClearCacheMethod[]=relating

[article]
DependentClassIdentifier[]=folder
DependentClassIdentifier[]=landing_page
ClearCacheMethod[]=object
ClearCacheMethod[]=parent
ClearCacheMethod[]=relating

[article_mainpage]
DependentClassIdentifier[]=folder
DependentClassIdentifier[]=landing_page
ClearCacheMethod[]=object
ClearCacheMethod[]=parent
ClearCacheMethod[]=relating

[article_subpage]
DependentClassIdentifier[]=article_mainpage
ClearCacheMethod[]=object
ClearCacheMethod[]=parent
ClearCacheMethod[]=relating
ClearCacheMethod[]=siblings

[blog_post]
DependentClassIdentifier[]=landing_page
DependentClassIdentifier[]=blog
ClearCacheMethod[]=object
ClearCacheMethod[]=parent
ClearCacheMethod[]=relating

[product]
DependentClassIdentifier[]=folder
DependentClassIdentifier[]=landing_page
ClearCacheMethod[]=object
ClearCacheMethod[]=parent
ClearCacheMethod[]=relating

[infobox]
DependentClassIdentifier[]=folder
ClearCacheMethod[]=object
ClearCacheMethod[]=parent
ClearCacheMethod[]=relating

[wiki_page]
DependentClassIdentifier[]=wiki_page
ClearCacheMethod[]=object
ClearCacheMethod[]=parent
ClearCacheMethod[]=relating

[banner]
DependentClassIdentifier[]=landing_page
ClearCacheMethod[]=object
ClearCacheMethod[]=parent
ClearCacheMethod[]=relating

[geo_article]
DependentClassIdentifier[]=landing_page
ClearCacheMethod[]=object
ClearCacheMethod[]=parent
ClearCacheMethod[]=relating
*/ ?>
