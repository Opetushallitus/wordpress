    jQuery(document).ready(function() {
        
    // Instantiate the Bloodhound suggestion engines
    var set1 = new Bloodhound({
        datumTokenizer: function (datum) {
            return Bloodhound.tokenizers.whitespace(datum.value);
        },
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        remote: {
            url: 'https://itest-oppija.oph.ware.fi/lo/autocomplete?lang=fi&term=%QUERY',
            filter: function (set1) {

                return $.map(set1.keywords, function (word) {                
                    return {
                        value: word
                    };
                });
            }
        }
    });

    var set2 = new Bloodhound({
        datumTokenizer: function (datum) {
            return Bloodhound.tokenizers.whitespace(datum.value);
        },
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        remote: {
            url: 'https://itest-oppija.oph.ware.fi/lo/autocomplete?lang=fi&term=%QUERY',
            filter: function (set2) {

                return $.map(set2.loNames, function (name) {                
                    return {
                        value: name
                    };
                });
            }
        }
    });

    // Initialize the Bloodhound suggestion engines
    set1.initialize();
    set2.initialize();

    // Instantiate the Typeahead UI

        $('.search-field').typeahead({
                highlight: true
            },
            {
                name: 'cat-keywords',
                displayKey: 'value',
                source: set1.ttAdapter(),
                templates: {
                    empty: [
                        '<div class="tt-empty-message">',
                        'No Results',
                        '</div>'
                    ].join('\n'),
                    header: '<span class="tt-tag-heading tt-tag-heading2">Hakusanaehdotukset</span>'
                } 
            },
            {
                name: 'cat-loNames',
                displayKey: 'value',
                source: set2.ttAdapter(),
                templates: {
                    empty: [
                        '<div class="tt-empty-message">',
                        'No Results',
                        '</div>'
                    ].join('\n'),
                    header: '<span class="tt-tag-heading tt-tag-heading2">Koulutukset ja tutkinnot</span>'
                } 
            });

    });