<?php /* #?ini charset="utf-8"?

[InformationCollectionSettings]
EmailReceiver=

[Session]
SessionNamePerSiteAccess=disabled

[SiteSettings]
LoginPage=embedded
AdditionalLoginFormActionURL=

[UserSettings]
RegistrationEmail=

[SiteAccessSettings]
RequireUserLogin=false
RelatedSiteAccessList[]
{% for siteAccess in relatedSiteAccessList %}
RelatedSiteAccessList[]={{ siteAccess }}
{% endfor %}
ShowHiddenNodes=false

[DesignSettings]
SiteDesign={{ designName }}
AdditionalSiteDesignList[]
AdditionalSiteDesignList[]=ngmore
AdditionalSiteDesignList[]=ezdemo
AdditionalSiteDesignList[]=ezflow
AdditionalSiteDesignList[]=base
AdditionalSiteDesignList[]=standard

[RegionalSettings]
Locale={{ siteAccessLocale }}
ContentObjectLocale={{ siteAccessLocale }}
ShowUntranslatedObjects=disabled
SiteLanguageList[]
{% for languageCode in siteLanguageList %}
SiteLanguageList[]={{ languageCode }}
{% endfor %}
TextTranslation=enabled

[ContentSettings]
TranslationList={{ translationList }}
EditDirtyObjectAction=usecurrent

[SearchSettings]
AllowEmptySearch=disabled
DelayedIndexing=enabled
SearchViewHandling=template

SearchClasses[]
SearchClasses[]=ng_blog_post
SearchClasses[]=ng_article
SearchClasses[]=ng_news
SearchClasses[]=ng_category
SearchClasses[]=ng_feedback_form
SearchClasses[]=ng_landing_page
SearchClasses[]=ng_category_page
SearchClasses[]=ng_video
SearchClasses[]=ng_audio
SearchClasses[]=file
*/ ?>
