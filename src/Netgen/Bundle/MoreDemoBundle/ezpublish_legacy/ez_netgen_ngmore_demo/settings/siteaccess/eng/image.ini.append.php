<?php /* #?ini charset="utf-8"?

[ImageMagick]
Filters[]=thumb=-resize 'x%1' -resize '%1x<' -resize 50%
Filters[]=centerimg=-gravity center -crop %1x%2+0+0 +repage
Filters[]=strip=-strip

[AliasSettings]
AliasList[]

# Netgen specific, ne smiju se vidjeti u administraciji
AliasList[]=ng_image_embed
AliasList[]=ng_image_line
AliasList[]=ng_image_full
AliasList[]=ng_image_block_item
AliasList[]=gallerylarge
AliasList[]=galleryfull
AliasList[]=gallerythumbnail
AliasList[]=medium

# ngresponsiveimage aliases

AliasList[]=imagefull
AliasList[]=i320
AliasList[]=i480
AliasList[]=i770
AliasList[]=i960
AliasList[]=i1540

[ng_responsive_full]
Reference=original
Filters[]=geometry/scalewidthdownonly=1000

[ng_responsive_half]
Reference=original
Filters[]=geometry/scalewidthdownonly=500

[imagefull]
Reference=original
Filters[]=geometry/scalewidthdownonly=1000

[i320]
Reference=original
Filters[]=geometry/scalewidthdownonly=320

[i480]
Reference=original
Filters[]=geometry/scalewidthdownonly=480

[i770]
Reference=original
Filters[]=geometry/scalewidthdownonly=770

[i960]
Reference=original
Filters[]=geometry/scalewidthdownonly=960

[i1540]
Reference=original
Filters[]=geometry/scalewidthdownonly=1540

# end ngresponsiveimage aliases


[ng_image_full]
Reference=
Filters[]
Filters[]=geometry/scalewidthdownonly=1170

[ng_image_line]
Reference=
Filters[]
Filters[]=geometry/scalewidthdownonly=330

[ng_image_embed]
Reference=
Filters[]
Filters[]=geometry/scalewidthdownonly=330

[ng_image_block_item]
Reference=
Filters[]
Filters[]=geometry/scalewidthdownonly=330

[gallerylarge]
Reference=
Filters[]
Filters[]=geometry/scalewidth=770

[galleryfull]
Reference=
Filters[]
Filters[]=geometry/scalewidthdownonly=1800

[gallerythumbnail]
Reference=
Filters[]
Filters[]=thumb=182
Filters[]=centerimg=96;72
Filters[]=strip=
*/ ?>
