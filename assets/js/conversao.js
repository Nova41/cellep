function Tracking(){
    var img = document.createElement('img');
    var goalId = 952506272;
    var randomNum = new Date().getMilliseconds();
    var value = 0;
    var label = '1s7wCIGYm2EQoK-YxgM';
    var url = encodeURI(location.href);
       
    var trackUrl = 'http://www.googleadservices.com/pagead/conversion/'+goalId+'/?random='+randomNum+'&value='+value+'&label='+label+'&guid=ON&script=0&url='+url;
    img.id = 'conversao';
    img.src = trackUrl;
    document.body.appendChild(img);
        
    setTimeout(function() {
     	jQuery('#conversao').remove();
          //console.log('teste');
    }, 3000);
        
 }