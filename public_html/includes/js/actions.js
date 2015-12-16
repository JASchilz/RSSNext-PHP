rssnext.actions = (function () {

    var wrapXMLWithContext = function (xml, context) {

        xml = '<comm><request context="' + context + '">' + xml;
        xml += '</request></comm>';

        return xml;
    };

    var getFeeds = function () {
    
        var xml = wrapXMLWithContext("", "get user feeds");

        var handler = function (response) {

            var these_feeds = response.getElementsByTagName('feed');

            for (var i=0; i<these_feeds.length; i++) {
                rssnext.utils.addFeedToList({
                    url: these_feeds[i].firstChild.nodeValue,
                    id: these_feeds[i].getAttribute("id"),
                });
            }
        };

        rssnext.ajax.sendAjax(xml, handler);
    };

    var removeFeedFromUser = function (thisFeed) {
    

        if (confirm("Remove " + thisFeed["url"] + " from your feeds?")) {

            var xml = '<feed id="' + thisFeed["id"] + '"/>';
            xml = wrapXMLWithContext(xml, "remove feed from user");

            var handler = function (response) {
                if (response.getElementsByTagName('feed').item(0) == null) {
                    alert("Error removing feed. Try refreshing the page.")
                } else {

                    var thisFeed = response.getElementsByTagName('feed')[0];
                    var thisFeedId = thisFeed.getAttribute("id");
                    $("#feedContainer" + thisFeedId).remove();
                }

            };
            rssnext.ajax.sendAjax(xml, handler);

        }

    };

    var addFeedToUser = function (thisFeed) {
    

        var xml = '<feed>' + thisFeed["url"] + '</feed>';
        xml = wrapXMLWithContext(xml, "add feed to user");

        var handler = function (response) {

            if (response.getElementsByTagName('feed').item(0) == null) {
                var thisError = response.getElementsByTagName('error')[0];
                alert(thisError.firstChild.nodeValue);
            } else {

                var thisFeed = response.getElementsByTagName('feed')[0];

                rssnext.utils.addFeedToList({
                    url: thisFeed.firstChild.nodeValue,
                    id: thisFeed.getAttribute("id")
                });

                document.getElementById('url-input').value = "";
            }
        };

        rssnext.ajax.sendAjax(xml, handler);
    };

    return {
        getFeeds: getFeeds,
        removeFeedFromUser: removeFeedFromUser,
        addFeedToUser: addFeedToUser
    }
}());


