{ezpagedata_set('show_path',false())}
{ezpagedata_set('site_title','Object moved')}

<div class="container kernel-error-4 clearfix">

    <h1>{"Object moved"|i18n("design/standard/error/kernel")}</h1>

    <p>{"The object is no longer available at this URL."|i18n("design/standard/error/kernel")}</p>
    <p>{"You should automatically be redirected to the new location. If not click %url."|i18n("design/standard/error/kernel",,
                                                                                              hash('%url',concat('<a href=',$parameters.new_location|ezurl(),'>',
                                                                                                                 $parameters.new_location|ezurl(),
                                                                                                                  '</a>')))}</p>

</div>
