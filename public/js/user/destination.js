$(document).ready(function(e) {
    if(typeof FileReader =='undefined')
    {
        if($.browser.msie===true)
        {
            if($.browser.version==6)
            {
                $("#photo").change(function(event){
                      var src = event.target.value;
                      var img = '<img src="'+src+'" width="100" height="130" />';
                      $("#destination").empty().append(img);
                      $("#showdelphoto").removeClass("disnon");
                  });
            }
            else if($.browser.version==7 || $.browser.version==8)
            {
                $("#photo").change(function(event){
                      $(event.target).select();
                      var src = document.selection.createRange().text;
                      var dom = document.getElementById('destination');
                      dom.filters.item('DXImageTransform.Microsoft.AlphaImageLoader').src= src;
                      dom.innerHTML = '';
                      $("#showdelphoto").removeClass("disnon");
                 });
            }
        }
        else if($.browser.mozilla===true)
        {
            $("#photo").change(function(event){
                if(event.target.files)
                {
                  for(var i=0;i<event.target.files.length;i++)
                  {
                      var img = '<img src="'+event.target.files.item(i).getAsDataURL()+'" width="100" height="130"/>';
                      $("#destination").empty().append(img);
                      $("#showdelphoto").removeClass("disnon");
                  }
                }
            });
        }
    }
    else
    {
	   $("#photo").change(function(e){
           for(var i=0;i<e.target.files.length;i++)
           {
                var file = e.target.files.item(i);
                if(!(/^image\/.*$/i.test(file.type)))
                {
                    continue;
                }
                var freader = new FileReader();
                freader.readAsDataURL(file);
                freader.onload=function(e)
                {
                    var img = '<img src="'+e.target.result+'" width="100" height="130"/>';
                    $("#destination").empty().append(img);
                    $("#showdelphoto").removeClass("disnon");
                }
           }
	   });
      var destDom = document.getElementById('destination');
      destDom.addEventListener('dragover',function(event){
          event.stopPropagation();
          event.preventDefault();
          },false);

      destDom.addEventListener('drop',function(event){
          event.stopPropagation();
          event.preventDefault();
          var img_file = event.dataTransfer.files.item(0);
          if(!(/^image\/.*$/.test(img_file.type)))
          {
              return false;
          }
          fReader = new FileReader();
          fReader.readAsDataURL(img_file);
          fReader.onload = function(event){
              destDom.innerHTML='';
              destDom.innerHTML = '<img src="'+event.target.result+'" width="100" height="130"/>';
              $("#showdelphoto").removeClass("disnon");
              };
      },false);
    }
    //$('#destination').css('margin-top','5px');
});