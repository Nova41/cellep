function Tracking(){
    
    //var google_conversion_id = 952506272;
    var google_conversion_id = 1013525665;
    var google_conversion_language = "en";
    var google_conversion_format = "3";
    var google_conversion_color = "ffffff";
    //var google_conversion_label = "1s7wCIGYm2EQoK-YxgM";
    var google_conversion_label = "B6KZCIqWsWEQodmk4wM";
    var google_remarketing_only = false;
    var google_conversion_page_url = encodeURI(location.href);
    
    var j = document.createElement("script");
    //var caminho = '//www.googleadservices.com/pagead/conversion.js';
    //var caminho = '/wp-content/themes/cellep/assets/js/exemplo.js';
    var caminho = '/wp-content/themes/cellep/assets/js/conversion.js';
    j.type = "text/javascript";
    j.id = 'conversao-j';
    j.src = caminho;
    document.body.appendChild(j);
    
    var img = document.createElement('img');
    
    var trackUrl = '//www.googleadservices.com/pagead/conversion/'+google_conversion_id+'/?label='+google_conversion_label+';guid=ON&amp;script=0&url='+google_conversion_page_url;
    img.id = 'conversao';
    img.width = '1';
    img.height = '1';
    img.style = 'border-style:none;';
    img.src = trackUrl;
    document.body.appendChild(img);
        
    setTimeout(function() {
     	jQuery('#conversao-j').remove();
     	jQuery('#conversao').remove();
          //console.log('teste');
    }, 10000);
        
 }