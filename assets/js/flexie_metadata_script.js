setTimeout(function(){  
    try {
        if(typeof flexie_metadata_script_object !== 'undefined'){
            if (typeof flexie_metadata_script_object.pageHit !== 'undefined') {
                if (flexie_metadata_script_object.pageHit == true && typeof window.FlexieSDKLoaded != 'undefined' ) {
                    FlexieSDK.trackPageHit({
                        identifierAlias: 'email',
                        identifierValue: flexie_metadata_script_object.registerEmail
                    }); 
                } 
            } else {
                if (flexie_metadata_script_object.email != 'undefined') {
                    FlexieSDK.trackPageHit({
                        hitEvent: 'pageview',
                        metadata: { 'type': flexie_metadata_script_object.objectType, 'data': flexie_metadata_script_object.metadata },
                        identifierAlias: 'email',
                        identifierValue: flexie_metadata_script_object.email
                    });
                } else {
                    FlexieSDK.trackPageHit({
                        hitEvent: 'pageview',
                        metadata: { 'type': flexie_metadata_script_object.objectType, 'data': flexie_metadata_script_object.metadata }
                    });
                }
            }        
        } else {
            FlexieSDK.trackPageHit();
        }
    } 
    catch(e) {
        console.log(e);
    }
}, 1000);
