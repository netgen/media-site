<?php /* #?ini charset="utf-8"?

[Session]
SessionNamePerSiteAccess=disabled

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
*/ ?>
