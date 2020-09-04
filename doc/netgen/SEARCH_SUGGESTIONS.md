Search spellcheck & suggestions
===============================

There's an implementation of the "did you mean" functionality for search. It's using Solr's
SpellCheck component to provide inline query suggestions based on other, similar, terms.

**Note:** This feature is available only with the Solr search engine.

Activation
----------

In order to use this feature, some adjustments have to be made on the Solr configuration.
This has been described in the documentation for Netgen eZ Platform Search Extra bundle:
https://docs.netgen.io/projects/search-extra/en/latest/reference/spellcheck_suggestions.html
