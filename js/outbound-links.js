jQuery(document).ready(function($) {
    var domains = $.parseJSON(data.domains);
    var utm = $.parseJSON(data.utm_data);
    var utm_string = '?';
    
    $.each( utm, function(k,v) {
        utm_string += k+'='+v+'&';
    });
    utm_string = utm_string.slice(0, -1);
    
    $('a:not([href*="' + document.domain + '"],[href^="#"],[href^="mailto:"])').each(function() {
        var url = $(this).attr('href');
        var check = url.slice(-1);
	if(check != "/") { url = url+"/"; } // check if url ends with /
        if(typeof url == 'undefined') { return; }
        /* Remove existing UTM */
        var utm_index = url.search("utm_source");
        /* if(utm_index != -1) { utm_index -= 1; url = url.slice(0, utm_index); } */
        
        if(utm_index == -1) { // By this code you can block adding (choose one removing or block adding for existing) */
        var host = $('<a>').prop('href', url).prop('hostname');
        var domain = host.replace('www.','');
        var utm_add = false;
        
        $.each( domains, function(k,v) { 
            v = v.replace('http://','');
            if( (v == host) || ($(v).is(host)) || (v == domain) || ($(v).is(domain)) ) { utm_add = true; return; }
        });
        
        if(utm_add) { 
            var changed_url = url+utm_string;
            $(this).attr('href', changed_url);
        }
        } // end of block if
    });
});