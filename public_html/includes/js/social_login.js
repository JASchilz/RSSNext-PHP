rssnext.social_login = (function() {

    // Initialize facebook
    window.fbAsyncInit = function () {
        FB.init({
            appId: '1578875419016175',
            cookie: true,
            xfbml: true,
            version: 'v2.1'
        });

    };

    // Load the facebook SDK
    (function (d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) {
            return;
        }
        js = d.createElement(s);
        js.id = id;
        js.src = "//connect.facebook.net/en_US/sdk.js";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));

    var statusChangeCallback = function(response) {
        console.log('statusChangeCallback');
        if (response.status === 'connected') {
            // Logged into your app and Facebook.
            window.location = "/social_login.php?provider=facebook";
        }
    };

    var checkLoginState = function() {
        FB.getLoginStatus(function (response) {
            statusChangeCallback(response);
        });
    };

    return {
        checkLoginState: checkLoginState
    }

}());

