    jQuery(document).ready(function() {


    var prefix = CookiePrefixResolver.getPrefix(window.location.host),
    key = prefix + 'i18next';

consle.log(key);

    var lang = jQuery.cookie(key);

        
    // Instantiate the Bloodhound suggestion engines
    var set1 = new Bloodhound({
        datumTokenizer: function (datum) {
            return Bloodhound.tokenizers.whitespace(datum.value);
        },
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        remote: {
            url: '/lo/autocomplete?lang=' + lang + '&term=%QUERY',
            wildcard: "%QUERY",
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
            url: '/lo/autocomplete?lang=' + lang + '&term=%QUERY',
            wildcard: "%QUERY",
            filter: function (set2) {

                return $.map(set2.loNames, function (name) {                
                    return {
                        value: name
                    };
                });
            }
        }
    });

    var word_suggestion;
    var degree_suggestion;    
        
    if(lang == 'fi') {
        var word_suggestion = 'Hakusanaehdotukset';
        var degree_suggestion = 'Koulutukset ja tutkinnot';
    } else {
        var word_suggestion = 'Sökordsförslag';
        var degree_suggestion = 'Kurser och examen';
    }    
        
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
                        ''
                    ].join('\n'),
                    header: '<span class="tt-tag-heading"><strong>' + word_suggestion + '</strong></span>'
                } 
            },
            {
                name: 'cat-loNames',
                displayKey: 'value',
                source: set2.ttAdapter(),
                templates: {
                    empty: [
                        ''
                    ].join('\n'),
                    header: '<span class="tt-tag-heading"><strong>' + degree_suggestion + '</strong></span>'
                } 
            });

    });
