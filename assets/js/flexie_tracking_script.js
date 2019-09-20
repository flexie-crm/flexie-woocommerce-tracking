if (typeof window.FlexieSDKLoaded == 'undefined' 
    || window.FlexieSDKLoaded == false) {
    window.FlexieSDKLoaded = false;
    window.FlexieDomain = flexie_tracking_script_object.domain;
    
    var head            = document.getElementsByTagName('head')[0];
    var script          = document.createElement('script');
    script.type         = 'text/javascript';
    script.src          = flexie_tracking_script_object.domain + '/media/js/flexie.js?1.7';
    script.onload       = function() {
        
        FlexieSDKLoaded = true;  
        if(typeof flexie_metadata_script_object == 'undefined') {
            FlexieSDK.trackPageHit();
        }
    };
    head.appendChild(script);
} else {
    if(typeof flexie_metadata_script_object == 'undefined') {
        FlexieSDK.trackPageHit();
    }
}