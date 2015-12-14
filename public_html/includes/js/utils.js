function addFeedToList(thisFeed)
{

    var containerTr = $("<tr id=feedContainer" + thisFeed["id"] + "></tr>");

    var infoTd = $("<td>" + thisFeed["url"] + "</td>");
    var removeDiv = $("<div class='remove'></div>");
    removeDiv.click(function () {
        removeFeedFromUser(thisFeed);});
    removeDiv.attr("data-for-feed-url", thisFeed["url"]);

    var actionTd = $("<td></td>");

    actionTd.append(removeDiv);
    containerTr.append(infoTd);
    containerTr.append(actionTd);

    $("#your-feeds").append(containerTr);
}

function isValidUrl(url)
{
    return url.match(/^(ht|f)tps?:\/\/[a-z0-9-\.]+\.[a-z]{2,4}\/?([^\s<>\#%"\,\{\}\\|\\\^\[\]`]+)?$/);
}

function handleURLSubmit()
{

    var url = document.getElementById('url-input').value;

    url = url.replace(/^\s+|\s+$/g,"");

    if (isValidUrl(url) || ((url = "http://" + url) && isValidUrl(url))) {
        addFeedToUser({url:url,});
    } else {
        alert("Bad URL " + url);
    }

}

function wrapXMLWithContext(xml, context)
{

    xml = '<comm><request context="' + context +'">' + xml;
    xml += '</request></comm>';

    return xml;
}