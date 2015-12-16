rssnext.utils = (function() {

    var addFeedToList = function (thisFeed) {
        var containerTr = $("<tr id=feedContainer" + thisFeed["id"] + "></tr>");

        var infoTd = $("<td>" + thisFeed["url"] + "</td>");
        var removeDiv = $("<div class='remove'></div>");
        removeDiv.click(function () {
            rssnext.actions.removeFeedFromUser(thisFeed);
        });
        removeDiv.attr("data-for-feed-url", thisFeed["url"]);

        var actionTd = $("<td></td>");

        actionTd.append(removeDiv);
        containerTr.append(infoTd);
        containerTr.append(actionTd);

        $("#your-feeds").append(containerTr);
    };

    var isValidUrl = function (url) {
        return url.match(/^(ht|f)tps?:\/\/[a-z0-9-\.]+\.[a-z]{2,4}\/?([^\s<>\#%"\,\{\}\\|\\\^\[\]`]+)?$/)
            || url.indexOf("http://localhost") === 0;
    };

    var handleURLSubmit = function () {

        var url = document.getElementById('url-input').value;

        url = url.replace(/^\s+|\s+$/g, "");

        if (isValidUrl(url) || ((url = "http://" + url) && isValidUrl(url))) {
            rssnext.actions.addFeedToUser({url: url,});
        } else {
            alert("Bad URL " + url);
        }

    };

    return {
        addFeedToList: addFeedToList,
        isValidUrl: isValidUrl,
        handleURLSubmit: handleURLSubmit
    }

}());