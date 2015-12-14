var url = '/ajax/handle.php';


function sendAjax(xml, handler)
{
    var ajaxRequest = new XMLHttpRequest();
    ajaxRequest.overrideMimeType('text/xml');
    ajaxRequest.open("POST", url, true);
    ajaxRequest.setRequestHeader('Content-Type', 'text/xml');

    xml = encodeURIComponent(xml);
    ajaxRequest.send(xml);

    ajaxRequest.onloadend=function () {
        if (checkErrors(this.readyState, this.status, this.responseXML) == 0) {
            handler(this.responseXML);
        }
    }
}


function checkErrors(ready, status, msg)
{
    var fail = 0;
    if (ready == 4 && msg == null) {
        fail = 1;
    }
    if (status != 200) {
        fail = 1;
    }
    if (status == 200 && msg.getElementsByTagName('error').length != 0) {
        var errorList = msg.getElementsByTagName('error');
        if (errorList.length > 0 && errorList.item(0).firstChild == null) {
        } else {
            for (var i=0; i<errorList.length; i++) {
                alert(errorList.item(i).firstChild.nodeValue);
            }
        }
        fail = 1;
    }
    return fail;
}
