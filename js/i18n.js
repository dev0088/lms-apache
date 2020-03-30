(function (window) {
    var jQuery;
    var smartVideoLocale = {
        /* The loaded JSON message store will be set on this object */
        msgStore: {},
        persistMsgStore: function (data) {
            if (window.localStorage) {
                localStorage.setItem('msgStore', JSON.stringify(data));
                this.msgStore = data;
            } else {
                this.msgStore = data;
            }
            var e = jQuery.Event('LSLocaleUpdated');
            jQuery(document).trigger(e);
        },
        setLanguage: function (lang, lsRepUrl) {
            jQuery.ajax({
                url: lsRepUrl + 'locales/' + lang + '.json',
                dataType: 'json',
                success: function (data) {
                    smartVideoLocale.persistMsgStore(data);
                },
                error: function (error) {
                    jQuery.getJSON(lsRepUrl + 'locales/en_US.json', function (data) {
                        smartVideoLocale.persistMsgStore(data);
                    });
                }
            });
        },
        initMsgStore: function (options) {
            var lang = options.lang;
            smartVideoLocale.setLanguage(lang, options.lsRepUrl);
        },
        init: function (options, jquery) {
            jQuery = jquery;
            var localMsgStore = '';
            if (!!window.localStorage) {
                localMsgStore = localStorage.getItem('msgStore');
                if (localMsgStore !== null) {
                    this.initMsgStore(options);
                    this.msgStore = JSON.parse(localMsgStore);
                } else {
                    this.initMsgStore(options);
                }
            } else {
                this.initMsgStore(options);
            }
        }
    };

    /* Expose i18n to the global object */
    window.smartVideoLocale = smartVideoLocale;

})(window);