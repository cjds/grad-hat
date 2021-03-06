
{{HTML::style('css/markdown.css');}}

<script type="text/x-mathjax-config">
  MathJax.Hub.Config({
    jax: ["input/TeX","output/HTML-CSS"],
    tex2jax: {inlineMath: [["$","$"],["\\(","\\)"]],mathsize: "90%",
    processEscapes: true},
    "HTML-CSS":{linebreaks:{automatic:true, width: "container"}},
     TeX: { noUndefined: { attributes: 
{ mathcolor: "red", mathbackground: "#FFEEEE", mathsize: "90%" } } }, 

  });
</script>


{{HTML::script('http://cdn.mathjax.org/mathjax/latest/MathJax.js?config=TeX-AMS-MML_HTMLorMML');}}

<style>
.votebtn:hover{
  cursor: pointer;
}

.wmd-preview{
  padding: 3px;
  word-wrap:break-word;
  font-family:"Open Sans", "Helvetica Neue", "Helvetica", Helvetica, Arial, sans-serif; 
}

.wmd-buttons i{
  padding:3px;
  margin-right: 6px;
}

#tutorial a{
  color:#fff;
}

#tutorial a:hover{
  text-decoration: underline;
}
</style>
<script type="text/javascript">
$(document).ready(function(){
var jsonPreview=    $.ajax({
                      url: "{{URL::to('json/getText')}}",
                      type:"POST",
                      dataType :'json'
                    });

var Preview = {
  delay: 150,  
converter :null,
  preview: null,
  buffer: null, 

  timeout: null,
  mjRunning: false,
  oldText: null,   
  init: function () {
    this.preview = document.getElementById("wmd-preview");
    this.buffer = document.getElementById("wmd-input");
    //this.converter= new Markdown.Converter();
  },

  Update: function () {
    if (this.timeout) {clearTimeout(this.timeout)}
    this.timeout = setTimeout(this.callback,this.delay);
  },

  CreatePreview: function () {
    
    Preview.timeout = null;
    if (this.mjRunning) return;
    var text = this.buffer.value;
    //console.log(text);
    if (text === this.oldtext) return;
    this.oldtext = text;
    jsonPreview.abort();
    jsonPreview=$.ajax({
                  url: "{{URL::to('json/getText')}}",
                  data:{text:text},
                  type:"POST",
                  dataType :'json'
                }).done(function(data) {
                    Preview.preview.innerHTML=(data.text);  
                    Preview.preview.style.display='block';
                    MathJax.Hub.Queue(
                      ["Typeset",MathJax.Hub,Preview.preview],
                      ["PreviewDone",Preview]
                    );
                });
    
    this.mjRunning = true;
    
    
  },

  PreviewDone: function () {
    this.mjRunning = false;
   
  }

};

          
    Preview.init();
    Preview.callback = MathJax.Callback(["CreatePreview",Preview]);
    Preview.callback.autoReset = true;  // make sure it can run more than once
    Preview.Update();

    $('#wmd-question').click(help);
    $('#wmd-help').click(help);

    function help(e){
      e.preventDefault();
        $('#helpdialog').foundation('reveal','open')
    }
    $('#wmd-input').focus(function(event) {
        $('#tutorial').css('display','block');
        $('#tutorial a').css('color','white');
    });


    $('#wmd-bold').click(function(e){
        $('#wmd-input').val(setUpmarkDownChar('**',$('#wmd-input'),'bold',true));
        $('section[role="main"]').css('display','none');
        $('section[role="main"]').css('display','block');
        Preview.Update();
    });

    $('#wmd-italics').click(function(e){
        $('#wmd-input').val(setUpmarkDownChar('_',$('#wmd-input'),'italics',true));
        Preview.Update();
    });

    $("#wmd-blockquote").click(function(e){
        text=blockMarkdownPara($('#wmd-input'),'>');
        $('#wmd-input').val(text);
        Preview.Update();
    });

    $("#wmd-code").click(function(e){
        text=blockMarkdownPara($('#wmd-input'),'    ');
        $('#wmd-input').val(text);
        Preview.Update();
    });

    $('#wmd-image').click(function(e){
        $('#imagedialog').foundation('reveal', 'open');
    });

    $('#wmd-ol').click(function(e){
        text=blockMarkdownPara($('#wmd-input'),'1. ');
        $('#wmd-input').val(text);
        Preview.Update();
    });

    $('#wmd-ul').click(function(e){
        text=blockMarkdownPara($('#wmd-input'),'* ');
        $('#wmd-input').val(text);
        Preview.Update();
    });

    $('#wmd-function').click(function(e){
        $('#functiondialog').foundation('reveal','open')
    });

    $( "#wmd-link" ).click(function(e) {
      e.preventDefault();
      var selectionStart=$("#wmd-input")[0].selectionStart;
      var selectionEnd=$("#wmd-input")[0].selectionEnd;
      if(selectionStart!=selectionEnd){
        var text=$('#wmd-input').val().substring(selectionStart,selectionEnd);
        $('#markdown_add_link input[name=link-description]').val(text);
      }
      $('#linkdialog').foundation('reveal', 'open');
    });    

    $('button.addeqnbtn').click(function(){
      $('#wmd-input').val(markdownAddChar('',$('#wmd-input')[0].selectionStart,$('#wmd-input')[0].selectionStart,$('#wmd-input').val(),'<math> '+$(this).attr('data-eqn')+'</math>',false));
      $('#functiondialog').foundation('reveal','close');
       Preview.Update();
    })

    $('#addLinkBtn').click(function(e) {
       //[This link](http://example.net/) 
       //markdown_add_link
       var link=$('#markdown_add_link input[name=link-href]').val();
       var text=$('#markdown_add_link input[name=link-description]').val();

       $('#markdown_add_link input[name=link-href]').val(''); //reset value to blank
       $('#markdown_add_link input[name=link-description]').val(''); //reset value to blank
         
       $('#wmd-input').val(markdownAddChar('',$('#wmd-input')[0].selectionStart,$('#wmd-input')[0].selectionStart,$('#wmd-input').val(),'['+text+']'+'('+link+')',false));

       $('#linkdialog').foundation('reveal', 'close');
        Preview.Update();
    });

      // Variable to store your files
      var files; 
      // Add events
      $('#markdown_add_image input[name=image-file]').on('change', prepareUpload);
       
      // Grab the files and set them to our variable
      function prepareUpload(event){
        files = event.target.files;
        
        document.getElementById('uploadFiletxt').value=$(this).val();
      }


    $('#addImageBtn').click(function(e){
        //var link
        var link=$('#markdown_add_image input[name=image-href]').val();
       var text=$('#markdown_add_image input[name=image-description]').val();
       
       if(link==''){
          var data=new FormData();
          $.each(files, function(key, value)
          {
            data.append('image', value);
          });
          $('#imagespinner').css('display','block');
          $.ajax({
            url: 'https://api.imgur.com/3/image',
            processData: false, 
            cache: false,
            contentType: false,
            headers: {
                'Authorization':'Client-ID bbbc01fbda8c501'
            },
            type: 'POST',
            data: data,
            dataType: 'json'
          })
          .done(function(data) {
            link=(data.data.link);
            $('#wmd-input').val(markdownAddChar('',$('#wmd-input')[0].selectionStart,$('#wmd-input')[0].selectionStart,$('#wmd-input').val(),'!['+text+']'+'('+link+')',false));
            $('#linkdialog').foundation('reveal', 'close');
            $('#imagespinner').css('display','none');
            Preview.Update();
          })
          
          .fail(function() {
            alert("Sorry, but that file can't be uploaded")
          });
          
       }
       else{
        $('#wmd-input').val(markdownAddChar('',$('#wmd-input')[0].selectionStart,$('#wmd-input')[0].selectionStart,$('#wmd-input').val(),'!['+text+']'+'('+link+')',false));
       $('#linkdialog').foundation('reveal', 'close');
        Preview.Update();
       }
       $('#markdown_add_image input[name=image-href]').val(''); //reset value to blank
       $('#markdown_add_image input[name=image-description]').val(''); //reset value to blank
         

       
        
    });

    function setUpmarkDownChar(addingString,inputElement,middlePart,isSymmetric){
        return markdownAddChar(addingString,inputElement[0].selectionStart,inputElement[0].selectionEnd,inputElement.val(),middlePart,isSymmetric);
    }

    function markdownAddChar(addingString,selectionStart,selectionEnd,text,middlePart,isSymmetric){
      var start=text.substring(0,selectionStart);
      var selection=text.substring(selectionStart,selectionEnd);
      var end=text.substring(selectionEnd);
      if(selectionStart!=selectionEnd){
        middlePart=selection;
      }

      if(isSymmetric)
        return (start+' '+addingString+middlePart+addingString+' '+end);
      else
        return (start+' '+addingString+middlePart+' '+end);      
    }

    function blockMarkdownPara(inputElement,addingString){
        var text=inputElement.val();
        console.log(text);
        var selectionStart=inputElement[0].selectionStart;
        var selectionEnd=inputElement[0].selectionEnd;
        var position=text.substring(0,selectionStart).lastIndexOf('\n');
        if(selectionStart!=selectionEnd){
          para=text.substring(position,selectionEnd).replace(/[\n]/g,'\n'+addingString+' ');
          para=para.replace('\r','\r'+addingString+' ');
          text=text.substring(0,position)+'\n'+addingString+' '+para+text.substring(selectionEnd);
        } 
        else{
          position=text.substring(0,selectionStart).lastIndexOf('\n');
          text=text.substring(0,position)+'\n'+addingString+' '+text.substring(position);
        }  
        return text;
    }

    $('#wmd-input').on('keyup paste change',function(e){
        Preview.Update();

    });

        $('.modalclose').click(function(e){
      e.preventDefault();
            $('.reveal-modal').foundation('reveal', 'close');

    });
  
});

</script>
<div class="wmd-panel small-12">
        <div data-alert class="alert-box" id='tutorial' style="display:none">
            <a href='#' id='wmd-help'>We use markdown for input. If you're new to it, you can learn more here</a>
            <a href="#" class="close">&times;</a>
          </div>
            <div id="wmd-button-bar" class='wmd-button-row '>
              <div class='wmd-buttons'>
                  <i class="fa fa-bold" id='wmd-bold'></i> 
                  <i class="fa fa-italic" id='wmd-italics'></i>
                  <i class="fa fa-chain" id='wmd-link'></i>
                  <i class="fa fa-quote-left" id='wmd-blockquote'></i>
                  <i class="fa fa-code" id='wmd-code'></i>
                  <i class="fa fa-picture-o" id='wmd-image'></i>
                  <i class="fa fa-list-ol" id='wmd-ol'></i>
                  <i class="fa fa-list-ul" id='wmd-ul'></i>
                  <i class="fa" id='wmd-function'>&fnof;</i>
                  <i class="fa fa-question" id='wmd-question'></i>
              </div>
              
            </div>
            
            <textarea name='wmd-input' class="wmd-input" id="wmd-input">{{$data}}</textarea>

        </div>
        <div id="wmd-preview" class="wmd-panel wmd-preview"></div>




      @include('layouts.dialogs')