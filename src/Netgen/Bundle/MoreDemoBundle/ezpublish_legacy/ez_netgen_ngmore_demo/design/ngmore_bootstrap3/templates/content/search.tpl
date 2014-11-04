{ezpagedata_set( 'show_path', false() )}

{def $search = false()}
{set $search_text = $search_text|trim}

{if $search_text|ne( '' )}
    {if $use_template_search}
        {set $page_limit = 10}

        {def $active_facet_parameters = array()}
        {if ezhttp_hasvariable( 'activeFacets', 'get' )}
            {set $active_facet_parameters = ezhttp( 'activeFacets', 'get' )}
        {/if}

        {def $date_filter = 0}
        {if ezhttp_hasvariable( 'date_filter', 'get' )}
            {set $date_filter = ezhttp( 'date_filter', 'get' )}
            {switch match = $date_filter}
                {case match = 1}
                    {def $date_filter_label = "Last day"|i18n( "design/standard/content/search" )}
                {/case}
                {case match = 2}
                    {def $date_filter_label = "Last week"|i18n( "design/standard/content/search" )}
                {/case}
                {case match = 3}
                    {def $date_filter_label = "Last month"|i18n( "design/standard/content/search" )}
                {/case}
                {case match = 4}
                    {def $date_filter_label = "Last three months"|i18n( "design/standard/content/search" )}
                {/case}
                {case match = 5}
                    {def $date_filter_label = "Last year"|i18n( "design/standard/content/search" )}
                {/case}
            {/switch}
        {/if}

        {def $filter_parameters = fetch( 'ezfind', 'filterParameters' )
             $default_search_facets = fetch( 'ezfind', 'getDefaultSearchFacets' )}

        {set $search = fetch(
            ezfind, search,
            hash(
                'query', $search_text,
                'offset', $view_parameters.offset,
                'class_id', ezini( 'SearchSettings', 'SearchClasses' ),
                'limit', $page_limit,
                'sort_by', hash( 'score', 'desc' ),
                'facet', $default_search_facets,
                'filter', $filter_parameters,
                'publish_date', $date_filter,
                'spell_check', array( false() ),
                'search_result_clustering', hash( 'clustering', false() )
            )
        )}

        {set $search_result = $search.SearchResult}
        {set $search_count = $search.SearchCount}
        {def $search_extras = $search.SearchExtras}
        {set $stop_word_array = $search.StopWordArray}
    {/if}

    {def $base_uri = concat( '/content/search?SearchText=', $search_text )}

    {* Build the URI suffix, used throughout all URL generations in this page *}
    {def $uri_suffix = ''}
    {foreach $active_facet_parameters as $facet_field => $facet_value}
        {set $uri_suffix = concat( $uri_suffix, '&', 'activeFacets['|rawurlencode, $facet_field|rawurlencode, ']'|rawurlencode, '=', $facet_value|rawurlencode )}
    {/foreach}

    {foreach $filter_parameters as $name => $value}
        {set $uri_suffix = concat( $uri_suffix, '&', 'filter[]'|rawurlencode, '=', $name|rawurlencode, ':'|rawurlencode, '"'|rawurlencode, $value|trim( '"' )|solr_quotes_escape|rawurlencode, '"'|rawurlencode )}
    {/foreach}

    {if gt( $date_filter, 0 )}
        {set $uri_suffix = concat( $uri_suffix, '&date_filter=', $date_filter|rawurlencode )}
    {/if}
{/if}

<div class="container content-search clearfix">

    <form action={"/content/search/"|ezurl} method="get" class="form-search">

        <h1 class="att-title">{"Search"|i18n( "design/ngmore/content/search" )}</h1>

        <div class="row">
            <div class="col-xs-12">
                <div class="control-group">
                    <input class="input-xlarge" placeholder="{'Search'|i18n( 'design/ngmore/content/search' )}" type="text" name="SearchText" id="Search" value="{$search_text|wash}" />
                    <button type="submit" class="btn" name="SearchButton">{'Search'|i18n('design/ngmore/content/search')}</button>
                </div>

                {if $search_text|ne( '' )}
                    {if $stop_word_array}
                        <div class="alert alert-warning">
                            {"The following words were excluded from the search"|i18n("design/base")}:
                            {foreach $stop_word_array as $stop_word}
                                {$stop_word.word|wash}{delimiter}, {/delimiter}
                            {/foreach}
                        </div>
                    {/if}

                    {if $search_count|eq( 0 )}
                        <div class="alert alert-error">
                            <h2>{'No results were found when searching for "%1".'|i18n( "design/ngmore/content/search", , array( $search_text|wash ) )}</h2>
                            {if $search_extras.hasError}
                                {$search_extras.error|wash}
                            {/if}
                        </div>
                    {else}
                        <div class="alert alert-success">
                            <h2>{'Search for "%1" returned %2 matches'|i18n( "design/ngmore/content/search", , array( $search_text|wash, $search_count ) )}</h2>
                        </div>
                    {/if}

                    {if $search_extras.spellcheck_collation}
                        {def $spell_url = concat( '/content/search/', $search_text|count_chars|gt( 0 )|choose( '', concat( '?SearchText=', $search_extras.spellcheck_collation|urlencode ) ) )|ezurl}
                        <p>{'Spell check suggestion: did you mean'|i18n( 'design/ezfind/search' )} <strong>{concat( '<a href=', $spell_url, '>' )}{$search_extras.spellcheck_collation|wash}</a></strong>?</p>
                    {/if}
                {/if}
            </div>
        </div>

        {if $search_text|ne( '' )}
            <div class="row">
                <div class="col-xs-12 col-sm-9 col-md-9 col-lg-9">
                    {if $search_count|gt( 0 )}
                        {include
                            uri = 'design:navigator/google.tpl'
                            page_uri = '/content/search'
                            page_uri_suffix = concat( '?SearchText=', $search_text|urlencode, $uri_suffix )
                            item_count = $search_count
                            view_parameters = $view_parameters
                            item_limit = $page_limit
                        }

                        <div id="search-result">
                            {foreach $search_result as $result}
                                {node_view_gui view = 'ezfind_line' content_node = $result}
                            {/foreach}
                        </div>

                        {include
                            uri = 'design:navigator/google.tpl'
                            page_uri = '/content/search'
                            page_uri_suffix = concat( '?SearchText=', $search_text|urlencode, $uri_suffix )
                            item_count = $search_count
                            view_parameters = $view_parameters
                            item_limit = $page_limit
                        }
                    {else}
                        <ul>
                            <li>{'Check spelling of keywords.'|i18n( 'design/ngmore/content/search' )}</li>
                            <li>{'Try changing some keywords (eg, "car" instead of "cars").'|i18n( 'design/ngmore/content/search' )}</li>
                            <li>{'Try searching with less specific keywords.'|i18n( 'design/ngmore/content/search' )}</li>
                            <li>{'Reduce number of keywords to get more results.'|i18n( 'design/ngmore/content/search' )}</li>
                        </ul>
                    {/if}
                </div>

                <div class="col-xs-12 col-sm-3 col-md-3 col-lg-3">
                    <p class="lead">{'Refine your search'|i18n( 'design/ngmore/content/search' )}</p>

                    {def $active_facets_count = 0}
                    {def $suffix = ''}

                    <div id="active-facet-list">
                        {foreach $default_search_facets as $key => $default_facet}
                            {if and( is_set( $default_facet.field ), is_set( $default_facet.name ) )}
                                {if array_keys( $active_facet_parameters )|contains( concat( $default_facet.field, ':', $default_facet.name  ) )}
                                    {def $facet_data = $search_extras.facet_fields.$key}

                                    {foreach $facet_data.nameList as $key2 => $facet_name}
                                        {if eq( $active_facet_parameters[concat( $default_facet.field, ':', $default_facet.name )], $facet_name )}
                                            {set $active_facets_count = sum( $key, 1 )}
                                            {set $suffix = $uri_suffix|
                                                explode( concat( '&', 'filter[]'|rawurlencode, '=', $facet_data.fieldList[$key2]|rawurlencode, ':'|rawurlencode, '"'|rawurlencode, $key2|solr_quotes_escape|rawurlencode, '"'|rawurlencode ) )|implode( '' )|
                                                explode( concat( '&', 'activeFacets['|rawurlencode, $default_facet.field|rawurlencode, ':'|rawurlencode, $default_facet.name|rawurlencode, ']'|rawurlencode, '=', $facet_name|rawurlencode ) )|implode( '' )
                                            }

                                            <p>
                                                <a class="btn btn-mini" href={concat( $base_uri, $suffix )|ezurl} title="{'Remove filter on '|i18n( 'design/ngmore/content/search' )}{$facet_name|wash}">
                                                    &times; <strong>{$default_facet.name}:</strong> {$facet_name|wash}
                                                </a>
                                            </p>
                                        {/if}
                                    {/foreach}

                                    {undef $facet_data}
                                {/if}
                            {/if}
                        {/foreach}

                        {* handle date filter here, manually for now. Should be a facet later on *}
                        {if gt( $date_filter, 0 )}
                            {set $active_facets_count = $active_facets_count|inc}
                            {set $suffix = $uri_suffix|explode( concat( '&date_filter=', $date_filter|rawurlencode ) )|implode( '' )}
                            <p>
                                <a class="btn btn-mini" href={concat( $base_uri, $suffix )|ezurl} title="{'Remove filter on '|i18n( 'design/ngmore/content/search' )}'{$date_filter_label}'">
                                    &times; <strong>{'Creation time'|i18n( 'extension/ezfind/facets' )}:</strong> {$date_filter_label|wash}
                                </a>
                            </p>
                        {/if}

                        {if ge( $active_facets_count, 2 )}
                            <p class="clear-all">
                                <a class="btn btn-mini btn-info" href={$base_uri|ezurl} title="{'Clear all'|i18n( 'extension/ezfind/facets' )}">
                                    &times; <strong>{'Clear all filters'|i18n( 'extension/ezfind/facets' )}</strong>
                                </a>
                            </p>
                        {/if}
                    </div>

                    <div id="facet-list">
                        {foreach $default_search_facets as $key => $default_facet}
                            {if and( is_set( $default_facet.field ), is_set( $default_facet.name ) )}
                                {if array_keys( $active_facet_parameters )|contains( concat( $default_facet.field, ':', $default_facet.name ) )|not}
                                    {def $facet_data = $search_extras.facet_fields.$key}

                                    {if $facet_data.nameList|count}
                                        <p><strong>{$default_facet.name|wash}</strong></p>
                                        <ul>
                                            {foreach $facet_data.nameList as $key2 => $facet_name}
                                                {if ne( $key2, '' )}
                                                    <li>
                                                        <span class="label facet-count">{$facet_data.countList[$key2]|wash}</span>
                                                        <a href={concat(
                                                            $base_uri,
                                                            '&', 'filter[]'|rawurlencode, '=', $facet_data.fieldList[$key2]|rawurlencode, ':'|rawurlencode, '"'|rawurlencode, $key2|solr_quotes_escape|rawurlencode, '"'|rawurlencode,
                                                            '&', 'activeFacets['|rawurlencode, $default_facet.field|rawurlencode, ':'|rawurlencode, $default_facet.name|rawurlencode, ']'|rawurlencode, '=', $facet_name|rawurlencode,
                                                            $uri_suffix
                                                        )|ezurl}>
                                                        <span class="label facet-name">{$facet_name|wash}</span>
                                                        </a>
                                                    </li>
                                                {/if}
                                            {/foreach}
                                        </ul>
                                    {/if}

                                    {undef $facet_data}
                                {/if}
                            {/if}
                        {/foreach}

                        {* date filtering here. Using a simple filter for now. Should use the date facets later on *}
                        {if eq( $date_filter, 0 )}
                            <p><strong>{'Creation time'|i18n( 'extension/ezfind/facets' )}</strong></p>
                            <ul>
                                <li>
                                    <a href={concat( $base_uri, '&date_filter=1', $uri_suffix )|ezurl}><span class="label facet-name">{"Last day"|i18n( "design/standard/content/search" )}</span></a>
                                </li>
                                <li>
                                    <a href={concat( $base_uri, '&date_filter=2', $uri_suffix )|ezurl}><span class="label facet-name">{"Last week"|i18n( "design/standard/content/search" )}</span></a>
                                </li>
                                <li>
                                    <a href={concat( $base_uri, '&date_filter=3', $uri_suffix )|ezurl}><span class="label facet-name">{"Last month"|i18n( "design/standard/content/search" )}</span></a>
                                </li>
                                <li>
                                    <a href={concat( $base_uri, '&date_filter=4', $uri_suffix )|ezurl}><span class="label facet-name">{"Last three months"|i18n( "design/standard/content/search" )}</span></a>
                                </li>
                                <li>
                                    <a href={concat( $base_uri, '&date_filter=5', $uri_suffix )|ezurl}><span class="label facet-name">{"Last year"|i18n( "design/standard/content/search" )}</span></a>
                                </li>
                            </ul>
                        {/if}
                    </div>
                </div>
            </div>
        {/if}
    </form>

</div>
