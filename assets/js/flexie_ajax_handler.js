(function($){
    $('body').on( 'added_to_cart', function(e){		
        if (typeof window.FlexieSDKLoaded != 'undefined' && window.FlexieSDKLoaded == true) {
            $.ajax({
                type: 'POST',
                url: flexie_ajax_object.ajax_url,
                data: {
                    action: 'flexie_add_to_cart'
                },
                success: function (response) {
                    try {
                        var result = JSON.parse(response);
                        if (result.email != 'undefined') {
                            FlexieSDK.trackPageHit({
                                hitEvent: 'pageview',
                                metadata: { 'type': result.objectType, 'data': result.metadata },
                                identifierAlias: 'email',
                                identifierValue: result.email
                            }); 
                        } else {
                            FlexieSDK.trackPageHit({
                                hitEvent: 'pageview',
                                metadata: { 'type': 'cart', 'data': result.metadata }
                            });
                        }
                    } catch(error){
                        console.log(error.message);
                    }                   
                },
                error: function(error, XHR) {
                    console.log(XHR);
                }
            });
        }     
    });
})(jQuery);