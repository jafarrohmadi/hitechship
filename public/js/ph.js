var page = require("webpage").create(),
    url = "http://hitechship.test/leafleat/01076450SKY96A7";

function onPageReady() {
    var htmlContent = page.evaluate(function () {
        return document.documentElement.outerHTML;
    });

   page.render('googleScreenShot' + '.png'); 
   phantom.exit();
}

page.open(url, function (status) {
    function checkReadyState() {
        setTimeout(function () {
            var readyState = page.evaluate(function () {
                return document.readyState;
            });

            if ("complete" === readyState) {
                onPageReady();
            } else {
                checkReadyState();
            }
        }, 10000);
    }

    checkReadyState();
});