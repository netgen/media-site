<?php /* #?ini charset="utf-8"?

[InformationCollectionSettings]
EmailReceiver=

[SiteSettings]
SiteName={{ siteName }}
DefaultPage=content/dashboard
LoginPage=custom

[UserSettings]
RegistrationEmail=

[SiteAccessSettings]
RequireUserLogin=true
RelatedSiteAccessList[]
{% for siteAccess in siteAccessList %}
RelatedSiteAccessList[]={{ siteAccess }}
{% endfor %}
ShowHiddenNodes=true

[DesignSettings]
SiteDesign=admin2
AdditionalSiteDesignList[]
AdditionalSiteDesignList[]=admin
AdditionalSiteDesignList[]=ezdemo

[RegionalSettings]
Locale={{ siteAccessLocale }}
ContentObjectLocale={{ siteAccessLocale }}
ShowUntranslatedObjects=enabled
SiteLanguageList[]
{% for languageCode in siteAccessLanguages %}
SiteLanguageList[]={{ languageCode }}
{% endfor %}
TextTranslation=disabled

[ContentSettings]
CachedViewPreferences[full]=admin_navigation_content=1;admin_children_viewmode=list;admin_list_limit=1
TranslationList={{ siteAccessTranslationList }}
*/ ?>
