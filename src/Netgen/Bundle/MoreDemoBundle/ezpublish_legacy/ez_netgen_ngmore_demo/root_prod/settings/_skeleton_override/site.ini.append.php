<?php /* #?ini charset="utf-8"?

[DatabaseSettings]
DatabaseImplementation=ezmysqli
Server=
Port=
User=
Password=
Database=
Charset=
Socket=disabled

[FileSettings]
VarDir=var/ezdemo_site

[ExtensionSettings]
ActiveExtensions[]={{ extensionName }}
ActiveExtensions[]=ngresponsiveimages
ActiveExtensions[]=ngmore
ActiveExtensions[]=ezfind
ActiveExtensions[]=ngclasslist
ActiveExtensions[]=xrowmetadata
ActiveExtensions[]=bccie
ActiveExtensions[]=birthday
ActiveExtensions[]=childrenindexer
ActiveExtensions[]=parentindexer
ActiveExtensions[]=hideuntildate
ActiveExtensions[]=ezclasslists
ActiveExtensions[]=ezchangeclass
ActiveExtensions[]=enhancedselection2
ActiveExtensions[]=ezmultiupload
ActiveExtensions[]=eztags
ActiveExtensions[]=ezjscore
ActiveExtensions[]=ezstarrating
ActiveExtensions[]=ezgmaplocation
ActiveExtensions[]=ezdemo
ActiveExtensions[]=ezwt
ActiveExtensions[]=ezflow
ActiveExtensions[]=ezie
ActiveExtensions[]=ezoe
ActiveExtensions[]=ezodf
ActiveExtensions[]=ezprestapiprovider

[Session]
SessionNameHandler=custom

[SiteSettings]
DefaultAccess={{ defaultAccess }}
SiteList[]
{% for siteAccess in siteList %}
SiteList[]={{ siteAccess }}
{% endfor %}
RootNodeDepth=1

[UserSettings]
LogoutRedirect=/

[SiteAccessRules]
Rules[]=access;disable
Rules[]=module;user/register
Rules[]=module;ezinfo/about
Rules[]=module;ezinfo/copyright
Rules[]=module;content/advancedsearch
Rules[]=module;settings/edit
Rules[]=module;visual

[SiteAccessSettings]
ForceVirtualHost=true
RemoveSiteAccessIfDefaultAccess=enabled
CheckValidity=false
AvailableSiteAccessList[]
{% for siteAccess in availableSiteAccessList %}
AvailableSiteAccessList[]={{ siteAccess }}
{% endfor %}
MatchOrder=host_uri
HostUriMatchMapItems[]
{% for hostUriMatchMapItem in hostUriMatchMapItems %}
HostUriMatchMapItems[]={{ hostUriMatchMapItem }}
{% endfor %}
HostMatchMapItems[]

[DesignSettings]
DesignLocationCache=enabled

[RegionalSettings]
TranslationSA[]

[MailSettings]
Transport=sendmail
AdminEmail=info@netgen.hr
EmailSender=
*/ ?>
