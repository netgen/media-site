<?php /* #?ini charset="utf-8"?

[InformationCollectionSettings]
EmailReceiver=

[Session]
SessionNamePerSiteAccess=disabled

[SiteSettings]
SiteName={{ siteName }}
LoginPage=embedded
AdditionalLoginFormActionURL=
MetaDataArray[]
MetaDataArray[author]=Netgen
MetaDataArray[description]={{ siteName }}
MetaDataArray[keywords]={{ siteName|lower }}

[UserSettings]
RegistrationEmail=

[SiteAccessSettings]
RequireUserLogin=false
RelatedSiteAccessList[]
{% for siteAccess in siteAccessList %}
RelatedSiteAccessList[]={{ siteAccess }}
{% endfor %}
ShowHiddenNodes=false

[DesignSettings]
SiteDesign={{ siteDesign }}
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
{% for languageCode in siteAccessLanguages %}
SiteLanguageList[]={{ languageCode }}
{% endfor %}
TextTranslation=enabled

[ContentSettings]
TranslationList={{ siteAccessTranslationList }}
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
SearchClasses[]=ng_video_external
SearchClasses[]=ng_audio
SearchClasses[]=file
SearchClasses[]=video
*/ ?>
