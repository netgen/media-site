{*

'subject'
'email_receiver'
'email_cc_receivers'
'email_bcc_receivers'
'email_sender'
'email_reply_to'
'redirect_to_node_id'

*}
{def $collected_email = false()}
{foreach $collection.attributes as $attribute}
    {if $attribute.contentclass_attribute.identifier|eq( 'email' )}
        {set $collected_email = $attribute}
    {/if}
{/foreach}
{set-block scope=root variable=content_type}text/html{/set-block}
{set-block scope=root variable=$subject}Sitename.com – {$collection.object.name} {if $collected_email.has_content} [{$collected_email.content|wash}]{/if}{/set-block}
{set-block scope=root variable=$email_receiver}{$object.data_map.recipient.content}{/set-block}
{* uncomment if you want to send email in behalf of submitter *}
{*set-block scope=root variable=$email_sender}{$collected_email.content|wash}{/set-block*}
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
   "http://www.w3.org/TR/html4/loose.dtd">
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="initial-scale=1.0">    <!-- So that mobile webkit will display zoomed in -->
    <meta name="format-detection" content="telephone=no"> <!-- disable auto telephone linking in iOS -->

{literal}
    <style type="text/css">
        body {-webkit-text-size-adjust:none; -ms-text-size-adjust:none;}
        body {margin:0; padding:0;}
    </style>
{/literal}
</head>
<body style="margin:0; padding:10px 0;" bgcolor="#ebebeb" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">

<br>

<!-- ### BEGIN CONTENT ### -->

{* * *}

<div style="font-weight: normal; font-size: 26px; color: #777777">
{$collection.object.name}
</div><br>

{"The following information was collected"|i18n("design/ezwebin/collectedinfomail/form")}:
<br><br>

<table border="0" cellpadding="0" cellspacing="0">
{foreach $collection.attributes as $attribute}
    <tr>
        <td width="40%" style="padding-right: 20px; padding-bottom: 10px; color: #777777" valign="top">
            {$attribute.contentclass_attribute_name|wash}:
        </td>
        <td width="60%" style="padding-bottom: 10px" valign="top">
            <strong>{attribute_result_gui view=info attribute=$attribute}</strong>
        </td>
    </tr>
{/foreach}
</table><br><br>


<br>

{* * *}

<br>
</body>
</html>
