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
