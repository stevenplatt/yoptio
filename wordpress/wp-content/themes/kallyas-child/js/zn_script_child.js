(function ($) {

    $(window).load(function () {

    	// YOUR CONTENT HERE
		
		//707
		var lochash    = location.hash.substr(1);
		var panel = getUrlVars()["panel"];
		//console.log(panel+'-1-By 707 : -->'+lochash);
		if((lochash && lochash == 'login_panel') || ( panel && panel == 'login')){
			jQuery('.zn-loginModalBtn').find('a').trigger('click');
		}
    });
	
})(jQuery);

function getUrlVars()
{
    var vars = [], hash;
    var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
    for(var i = 0; i < hashes.length; i++)
    {
        hash = hashes[i].split('=');
        vars.push(hash[0]);
        vars[hash[0]] = hash[1];
    }
    return vars;
}