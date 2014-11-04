{set $classification = cond( and(is_set( $align ), $align ), concat( $classification, ' object-', $align ), $classification )}
<table class="{if $classification}{$classification|wash}{else}table{/if}{if is_set($responsive)} responsive{/if}" style="{if ne($width|trim,'')}width: {$width};{/if}{if ne($border|trim,'')}border: {$border};{/if}padding: {first_set($cellpadding, '2')};border-spacing:0;border-collapse: collapse;" {if and(is_set( $title ), $title)} title="{$title|wash}"{/if}>
{if and(is_set( $caption ), $caption)}<caption>{$caption|wash}</caption>{/if}
{$rows}
</table>
