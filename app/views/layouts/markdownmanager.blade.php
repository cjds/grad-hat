
{{HTML::style('css/markdown.css');}}
{{HTML::script('js/markdown/Markdown.Converter.js');}}
{{HTML::script('js/markdown/Markdown.Sanitizer.js');}}
{{HTML::script('js/markdown/Markdown.Editor.js');}}

{{HTML::style('css/markdown.css');}}
<script type="text/x-mathjax-config">
  MathJax.Hub.Config({
    jax: ["input/TeX","output/HTML-CSS"],
    tex2jax: {inlineMath: [["$","$"],["\\(","\\)"]], displayMath: [ ["$$","$$"] ],mathsize: "90%",
    processEscapes: true},
    "HTML-CSS":{linebreaks:{automatic:true}},
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
}

</style>
<script type="text/javascript">
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
    this.converter= new Markdown.Converter();
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
    text=this.converter.makeHtml(text);
    text=text.replace(/[\$]/gi,'\ \\$');
    text = text.replace(/<\/math>/gi,'$$');
    text = text.replace(/<math>/gi,'$$');
    this.preview.innerHTML=text;
    //console.log(text);
    this.mjRunning = true;
    MathJax.Hub.Queue(
      ["Typeset",MathJax.Hub,this.preview],
      ["PreviewDone",this]
    );
  },

  PreviewDone: function () {
    this.mjRunning = false;
   
  }

};
</script>

<script type='text/javascript'>
  $(document).ready(function(){
          
    Preview.init();
    Preview.callback = MathJax.Callback(["CreatePreview",Preview]);
    Preview.callback.autoReset = true;  // make sure it can run more than once

    $('#wmd-bold').click(function(e){
        $('#wmd-input').val(setUpmarkDownChar('**',$('#wmd-input'),'bold',true));
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

    $('#addImageBtn').click(function(e){
        //var link
        var link=$('#markdown_add_image input[name=image-href]').val();
       var text=$('#markdown_add_image input[name=image-description]').val();

       $('#markdown_add_image input[name=image-href]').val(''); //reset value to blank
       $('#markdown_add_image input[name=image-description]').val(''); //reset value to blank
         
       $('#wmd-input').val(markdownAddChar('',$('#wmd-input')[0].selectionStart,$('#wmd-input')[0].selectionStart,$('#wmd-input').val(),'['+text+']'+'('+link+')',false));

       $('#linkdialog').foundation('reveal', 'close');
        Preview.Update();
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

                
<div class="wmd-panel">
            <div id="wmd-button-bar" class='wmd-button-row '>
              <div class='wmd-buttons'>
                  <span id='wmd-bold'></span>
                  <span id='wmd-italics'></span>
                  <span id='wmd-link'></span>
                  <span id='wmd-blockquote'></span>
                  <span id='wmd-code'></span>
                  <span id='wmd-image'></span>
                  <span id='wmd-ol'></span>
                  <span id='wmd-ul'></span>
                  <span id='wmd-function'></span>
              </div>
              
            </div>
            <textarea name='wmd-input' class="wmd-input" id="wmd-input">{{$data}}</textarea>
        </div>
        <div id="wmd-preview" class="wmd-panel wmd-preview"></div>


      <br>

      @include('layouts.dialogs')