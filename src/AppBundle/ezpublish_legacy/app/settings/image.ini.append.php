<?php /* #?ini charset="utf-8"?

[ImageMagick]
IsEnabled=true
ExecutablePath=/usr/bin
Executable=convert
PreParameters=+profile "*"

[MIMETypeSettings]
Quality[]
Quality[]=image/jpeg;90
Quality[]=image/webp;90

# The global conversion rules
# Most will be converted to jpeg except gif and xpms
ConversionRules[]
ConversionRules[]=image/gif;image/png
ConversionRules[]=image/x-xpixmap;image/png
ConversionRules[]=image/webp;image/webp
# force aliases from jpeg originals to be generated as webp
ConversionRules[]=image/jpeg;image/webp
# force aliases from originals in any non-specified format to be generated as webp
#ConversionRules[]=*;image/webp
ConversionRules[]=*;image/jpeg

[OutputSettings]
# A list of MIME types that are allowed as output type
# This determines which formats you want your web page to display
AllowedOutputFormat[]=image/webp

[ImageMagick]
QualityParameters[]=image/webp;-quality %1
MIMETagMap[]=image/webp;WEBP

*/ ?>
