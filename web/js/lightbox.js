    var activityIndicatorOn = function()
    {
        $( '<div id="imagelightbox-loading"><i class="fa fa-circle-o-notch fa-spin fa-lg"></i></div>' ).appendTo( 'body' );
    },
    activityIndicatorOff = function()
    {
        $( '#imagelightbox-loading' ).remove();
    },

    overlayOn = function()
    {
        $( '<div id="imagelightbox-overlay"></div>' ).appendTo( 'body' );
    },
    overlayOff = function()
    {
        $( '#imagelightbox-overlay' ).remove();
    },

    closeButtonOn = function( instance )
    {
        $( '<button type="button" id="imagelightbox-close" title="Close"><i class="fa fa-times fa-lg"></i></button>' ).appendTo( 'body' ).on( 'click touchend', function(){ $( this ).remove(); instance.quitImageLightbox(); return false; });
    },
    closeButtonOff = function()
    {
        $( '#imagelightbox-close' ).remove();
    };

    var instanceC = $( '[data-lightbox]' ).imagelightbox(
    {
        onStart:        function() { overlayOn(); closeButtonOn( instanceC ); },
        onEnd:          function() { closeButtonOff(); overlayOff(); activityIndicatorOff(); },
        onLoadStart:    function() { activityIndicatorOn(); },
        onLoadEnd:      function() { activityIndicatorOff(); }
    });