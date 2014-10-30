jQuery(document).ready(function($) {
    
    
    $('.theme-list').on('click', '.theme-select', function(){
        $("#demo-form #demo_theme").val($(this).data('theme'));
    });
    
    
});


jQuery(document).ready(function($){
    
    if ($('div[data-demo-building]').length) {
        checkStatus();
    } 
});

var statustimer;
var waitCount=0;
var buildCount=0;
function checkStatus() {
    var el = $('div[data-demo-building]');
    var id = el.data('demo');
    $.ajax({
          url: '/check/'+id
    })
    .done(function(response) {
        switch(response.status) {
            case 'building': 
                el.append('.');
                buildCount ++;
                statustimer = setTimeout(checkStatus, 3000);
                break;
            case 'waiting':
                el.append('.');
                waitCount ++;
                statustimer = setTimeout(checkStatus, 3000);
                break;
            case 'complete':
                el.hide();
                $('.success-message .live-url a').attr('href', response.url);
                $('.success-message .admin-url a').attr('href', response.url+'/bolt');
                $('.success-message').show();
        }
        
    });
}