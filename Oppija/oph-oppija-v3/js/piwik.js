var siteDomain = document.domain;

var piwikSiteId;
switch (siteDomain) {
    case "opintopolku.fi":
        piwikSiteId = 4;
        break;
    case "studieinfo.fi":
        piwikSiteId = 13;
        break;
    case "studyinfo.fi":
        piwikSiteId = 14;
        break;
    case "virkailija.opintopolku.fi":
        piwikSiteId = 3;
        break;
    case "testi.opintopolku.fi":
    case "testi.studieinfo.fi":
    case "testi.studyinfo.fi":
        piwikSiteId = 1;
        break;
    case "testi.virkailija.opintopolku.fi":
        piwikSiteId = 5;
        break;
    case "demo.opintopolku.fi":
    case "demo.studieinfo.fi":
    case "demo.studyinfo.fi":
        piwikSiteId = 15;
        break;
    default:
        piwikSiteId = 2; // Kehitys
}

var _paq = _paq || [];
_paq.push(["setDocumentTitle", document.domain + "/" + document.title]);
_paq.push(["trackPageView"]);
_paq.push(["enableLinkTracking"]);

(function () {
    var u = (("https:" == document.location.protocol) ? "https" : "http") + "://analytiikka.opintopolku.fi/piwik/";
    _paq.push(["setTrackerUrl", u + "piwik.php"]);
    _paq.push(["setSiteId", piwikSiteId]);
    var d = document, g = d.createElement("script"), s = d.getElementsByTagName("script")[0];
    g.type = "text/javascript";
    g.defer = true;
    g.async = true;
    g.src = u + "piwik.js";
    s.parentNode.insertBefore(g, s);
})();

// KEHA:n Matomo
var matomoSiteUrl;
switch (siteDomain) {
    case "opintopolku.fi":
    case "studieinfo.fi":
    case "studyinfo.fi":
        matomoSiteUrl = "https://analytiikka.ahtp.fi/";
        break;
    default:
        matomoSiteUrl = "https://keha-matomo-sdg-qa-qa.azurewebsites.net/"; // Testi
}
var cookieconsentSettings = {
    // Urls where matomo files can be found on the (matomo) server.
    matomoSiteUrl: matomoSiteUrl,
    matomoSiteId: "8",
    // Params that are included in the tracking requests. See https://developer.matomo.org/api-reference/tracking-api
    includedParams: ["idsite", "rec", "action_name", "url", "_id", "rand", "apiv"],
};
var hasInit = false;
var initMatomoTracker = function () {
    try {
        if (hasInit) return;
        hasInit = true;
        var tracker;
        if (typeof Matomo !== 'undefined') {
            tracker = Matomo;
        } else {
            tracker = Piwik;
        }
        var url = cookieconsentSettings.matomoSiteUrl;
        var fixedUrl = url.charAt(url.length - 1) === '/' ? url : url + '/';
        matomoTracker = tracker.getTracker(fixedUrl + "matomo.php", cookieconsentSettings.matomoSiteId);
        var customRequestProcess = function (request) {
            try {
                var pairs = request.split("&");
                var requestParametersArray = [];
                for (var index = 0; index < pairs.length; ++index) {
                    var pair = pairs[index].split("=");
                    if (cookieconsentSettings.includedParams.indexOf(pair[0]) === -1) {
                        continue;
                    }
                    requestParametersArray.push(pair[0] + "=" + pair[1]);
                }
                var osIndex = navigator.userAgent.indexOf(")");
                var ua =
                    osIndex !== -1
                        ? navigator.userAgent.substring(0, osIndex + 1)
                        : "Mozilla/5.0";
                requestParametersArray.push("ua=" + ua);
                return requestParametersArray.join("&");
            } catch (err) {
                return request;
            }
        };
        matomoTracker.setCustomRequestProcessing(customRequestProcess);
        matomoTracker.trackPageView();
        matomoTracker.enableLinkTracking();
    } catch (err) {
    }
};
if (typeof Matomo === 'undefined') {
    window.matomoAsyncInit = initMatomoTracker;
    window.piwikAsyncInit = initMatomoTracker;
} else {
    initMatomoTracker();
}