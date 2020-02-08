/*  TO DO LIST

- Adauga optiune de adaugare obiect nou pentru capitol. (Bafta coaie);

*/

var WEBSITE_NAME = "website";

(function($) {
  "use strict"; // Start of use strict

  $("#success-alert").hide();
//Confirm('Go to Google', 'Are you sure you want to visit Google', 'Yes', 'Cancel', function None(){});

  function AlertSucces(object) {
    $("#success-alert").fadeTo(1500, 500).slideUp(500, function(){//ALERT SUS 
      $("#success-alert").slideUp(500);
    });

    object.css("border-color", "#28c96e");
    setTimeout(function (){
      object.css("border-color", "#E0E0E0");
    }, 2500)
  }

  function Confirm(title, msg, $true, $false, callback, lectie, object) { /*change*/
        var $content =  "<div class='dialog-ovelay'>" +
                        "<div class='container-fluid dialog'><header>" +
                         " <h3> " + title + " </h3> " +
                         "<i class='fa fa-close'></i>" +
                     "</header>" +
                     "<div class='dialog-msg'>" +
                         " <p> " + msg + " </p> " +
                     "</div>" +
                     "<footer>" +
                         "<div class='controls'>" +
                             " <button class='button button-danger doAction'>" + $true + "</button> " +
                             " <button class='button button-default cancelAction'>" + $false + "</button> " +
                         "</div>" +
                     "</footer>" +
                  "</div>" +
                "</div>";
         $('body').prepend($content);
      $('.doAction').click(function () {
        callback(lectie, object);
        $(this).parents('.dialog-ovelay').fadeOut(500, function () {
          $(this).remove();
        });
      });
$('.cancelAction, .fa-close').click(function () {
        $(this).parents('.dialog-ovelay').fadeOut(500, function () {
          $(this).remove();
        });
      });
      $('body').get(0).scrollIntoView();
   }

    // Add to RegExp prototype
  RegExp.prototype.execAll = function(string) {
    var matches = [];
    var match = null;
    while ( (match = this.exec(string)) != null ) {
      var matchArray = [];
      for (var i in match) {
        if (parseInt(i) == i) {
          matchArray.push(match[i]);
        }
      }
      matches.push(matchArray);
    }
    return matches;
  }

  var lectie_selectata = 0;
  var capitol_selectat = 0;

  var poza_veche_lectie;
  var poza_noua_lectie;

  var poza_noua_element;

  var capitol_data;
  var calculators = [];
  var images = [];

  function sleep(millis){//Pentru debug
      var date = new Date();
      var curDate = null;
      do { curDate = new Date(); }
      while(curDate-date < millis);
  }

  function getBase64Image(img) {
    var canvas = document.createElement("canvas");
    canvas.width = img.width;
    canvas.height = img.height;
    var ctx = canvas.getContext("2d");
    ctx.drawImage(img, 0, 0);
    var dataURL = canvas.toDataURL("image/png");
    return dataURL.toString();
  }

  $.fn.swapWith = function(that) {
    var $this = this;
    var $that = $(that);
    
    // create temporary placeholder
    var $temp = $("<div>");
    
    // 3-step swap
    $this.before($temp);
    $that.before($this);
    $temp.after($that).remove();
    
    
    return $this;
  }

  $(document).ready(function() {

    /*$( window ).resize(function() {
      console.log(jQuery(window).width());
    });*/

    $("body").tooltip({ selector: '[data-toggle=tooltip]' });

      $("#LectieSelect").change(function() { //Cand se selecteaza lectia
        lectie_selectata = $("#LectieSelect").val();

        if(lectie_selectata == -1) { //Lectie noua

          //CURATENIE
          Remove_Capitole_Options();
          //Activam lista capitole de mai jos :)
          $("#CapitolSelect").prop("disabled", false);
          //Stergem continutul specific fiecarui capitol
          $('#continutCapitol').empty();
          $('#continutCapitol').hide();
          $('#titlu-capitole').hide();
          $('#CapitolSelectArea').hide();

          $('#continutLectie').empty();
          $('#continutLectie').css("display", "block");
          $("#titlu-lectie").text('Lectie noua');
          $("#titlu-lectie").show();

          html = 
             '<div class="obiect-lista">\
                <div class="form-row">\
                  <label for="NumeNouLectie">Numele Lectiei</label>\
                  <div class="input-group">\
                    <input type="text" class="form-control" id="NumeNouLectie" data-dbid="'+lectie_selectata+'" placeholder="Fara nume" value="Fara nume">\
                    <div class="obiect-icons">\
                      <i class="fas fa-plus-circle fa-2x" data-toggle="tooltip" data-placement="bottom" title="Salveaza noua lectie" id="new-titlu-lectie"></i>\
                    </div>\
                  </div>\
                </div>\
              </div>\
              ';
          $('#continutLectie')//Adaugam doar sectiunea de nume dupa care urmeaza sa editeze noua lectie
          .html(html);
        }
        else { //Lectie deja existenta

          //console.log(lectie_selectata);
          Remove_Capitole_Options();
          //Activam lista capitole de mai jos :)
          $("#CapitolSelect").prop("disabled", false);
          //Stergem continutul specific fiecarui capitol
          $('#continutCapitol').empty();
          $('#continutCapitol').hide();
          $('#titlu-capitole').hide();
          //Incarcam lista cu Capitolele specifice lectiei selectate
          AfiseazaCapitole(lectie_selectata);

          var nume_lectie = $("#LectieSelect option[value='"+lectie_selectata+"']").text(); 
          var idpos = nume_lectie.indexOf(" (ID:");
          nume_lectie = nume_lectie.substr(0, idpos);


          //Acum legat de modificarile lectiei
          $('#CapitolSelectArea').show();
          $('#continutLectie').css("display", "block");
          $("#titlu-lectie").text('Lectie');
          $("#titlu-lectie").show();

          var loc = window.location.pathname;
          var dir = loc.substring(0, loc.lastIndexOf('/'));
          var url = dir+"/img/Lectie_"+lectie_selectata+".png";

          var screen_width = jQuery(window).width();
          //console.log(screen_width);
          var html;

          //Buton delete
          html = 
            '<div class="obiect-lista">\
              <div class="form-row">\
                <label for="NumeStergereLectie">Doresti stergerea lectiei?</label>\
                <div class="input-group">\
                  <button type="button" class="btn btn-danger" data-dbid="'+lectie_selectata+'" id="delete-lectie">Sterge lectia</button>\
                </div>\
              </div>\
            </div>\
            ';

          //Nume Lectie
          html += 
             '<div class="obiect-lista">\
                <div class="form-row">\
                  <label for="NumeLectie">Numele Lectiei</label>\
                  <div class="input-group">\
                    <input type="text" class="form-control" id="NumeLectie" data-dbid="'+lectie_selectata+'" placeholder="'+nume_lectie+'" value="'+nume_lectie+'">\
                    <div class="obiect-icons">\
                      <i class="fas fa-save fa-2x" data-toggle="tooltip" data-placement="bottom" title="Salveaza noul nume" id="save-titlu-lectie"></i>\
                      <i class="fas fa-undo fa-2x" data-toggle="tooltip" data-placement="bottom" title="Revino la vechiul nume" id="undo-titlu-lectie"></i>\
                    </div>\
                  </div>\
                </div>\
              </div>\
              ';
          //Incarca poza lectiei curenta pentru a vedea versiunea de dinainte
          html += 
           '<div class="obiect-lista">\
              <div class="form-row">\
                <label for="PozaLectie">Poza Lectiei (Atentie! Format: 450X325!)</label>\
                <div class="input-group">\
                  <div class="custom-file">\
                    <input type="file" class="form-control-file" id="PozaLectie" data-dbid="'+lectie_selectata+'">\
                    <label class="custom-file-label" for="PozaLectie">Alege poza pentru lectie</label>\
                  </div>\
                  <div class="obiect-icons">\
                    <i class="fas fa-save fa-2x" data-toggle="tooltip" data-placement="bottom" title="Salveaza noua poza" id="save-poza-lectie"></i>\
                    <i class="fas fa-undo fa-2x" data-toggle="tooltip" data-placement="bottom" title="Revino la vechia poza" id="undo-poza-lectie"></i>\
                  </div>\
                </div>\
                <div>\
                <img src="'+url+'" class="img-fluid" alt="Responsive image" id="PreviewImagineLectie"></img>\
                </div>\
              </div>\
            </div>\
            ';

        $('#continutLectie')//Incarcare elemente specifice continutului lectiilor
        .html(html);

          if(screen_width<576)//ecran mic
          {
            var obiect_lista = $('#continutLectie .obiect-lista');
            var cnt;
            //Pentru fiecare obiect din continutul lectiei elimina '.input-group' si baga iconitele intr-un div parinte .under-obiect
            obiect_lista.each(function(index){ 
              //$(this).find('.input-group').css("background-color", "red");
              cnt = $(this).find('.input-group').contents(); //Stergem .input-group
              $(this).find('.input-group').replaceWith(cnt);
              $(this).find('i').wrapAll('<div class="under-obiect"></div>');

              var obiect_icons = $(this).find('.obiect-icons').detach(); //Un refresh pentru ca nu ia dimensiunile corecte
              $(this).append(obiect_icons);
            });
          }

        var myImage = new Image();
          myImage.onload = function(){

          poza_veche_lectie = getBase64Image(myImage); //Conversie poza normala in base64 pentru backup
        }
        myImage.src = url;
      }    
    });
    
    $("#CapitolSelect").change(function() {

      capitol_selectat = $("#CapitolSelect").val();
      if(capitol_selectat == -1) {//Adaugare capitol nou

        $('#continutCapitol').empty();
        $('#continutCapitol').show();
        var html = '<div class="obiect-lista">\
                  <div class="form-row">\
                    <label for="NumeNouCapitol">Numele Capitolului</label>\
                    <div class="input-group">\
                      <input type="text" class="form-control" id="NumeNouCapitol" data-dbid="'+capitol_selectat+'" placeholder="Capitol nou" value="Capitol nou">\
                      <div class="obiect-icons">\
                        <i class="fas fa-plus-circle fa-2x" id="new-titlu-capitol" data-toggle="tooltip" data-placement="bottom" title="Salveaza noul nume"></i>\
                      </div>\
                    </div>\
                  </div>\
               </div>';
        $('#continutCapitol').html(html);
        $('#titlu-capitole').show();
      }
      else {

        $('#CapitolSelect option[value="0"]').remove();
        $('#continutCapitol').empty();
        //console.log(capitol_selectat);
        AfiseazaContinutul(capitol_selectat, lectie_selectata); //Afisare continut capitol selectat
      }

    });

      //---------------CONTINUT LECTIE---------------
      $("#continutLectie")
      .on('click', '#undo-titlu-lectie', function(){

        var object = this;
        var nume_vechi = $("#NumeLectie").attr('placeholder');
        $("#NumeLectie").val(nume_vechi);

        var dbid = $("#NumeLectie").attr('data-dbid');

        $.ajax({
           url: 'save.php', //This is the current doc
           type: "POST", 
           data: {
            'tip_salvare': 2, //Salvare_nume_lectie
            'id' : dbid,
            'valoare': nume_vechi
           },
           success: function(data){
              console.log("s-a salvat numele vechi al lectiei");
              AlertSucces($(object).closest('.obiect-lista'));
           }
      });

        $('#LectieSelect option[value="'+ dbid + '"]').text(nume_vechi+" (ID: "+dbid+")"); //Schimba numele si in lista //Undo denumire titlu

      })

      .on('click', '#new-titlu-lectie', function(){

          var object = this;
          var nume_nou = $("#NumeNouLectie").val();

          var dbid = $("#NumeNouLectie").attr('data-dbid'); //-1 mereu

          $.ajax({
             url: 'save.php', //This is the current doc
             type: "POST", 
             data: {
              'tip_salvare': 2, //Salvare_nume_lectie
              'id' : dbid,
              'valoare': nume_nou
             },
             success: function(data){
                console.log("s-a inserat o noua lectie");
                AlertSucces($(object).closest('.obiect-lista'));
                var insert_id = parseInt(data);
                $('#LectieSelect')
                  .append($('<option>', {
                value: insert_id,
                selected: false,
                text: nume_nou + ' (ID:'+insert_id+')'
                }));

                var lectie_inserata_obj = $('#LectieSelect option[value="'+ insert_id + '"]');
                var text_lectie_noua_obj = $('#LectieSelect option[value="'+ insert_id + '"]').prev();
                lectie_inserata_obj.swapWith(text_lectie_noua_obj); //Swap cu textul de adaugare lectie noua
             }
          });
          //$('#LectieSelect option[value="'+ dbid + '"]').text(nume_nou+" (ID: "+dbid+")"); //Schimba numele si in lista //Undo denumire titlu
      })

      .on('click', '#delete-lectie', function() { //Salvare denumire titlu by userinput

          var object = this;

          var dbid = $(this).attr('data-dbid');

          //StergeLectie(dbid, object);
          Confirm('Stergere lectie', 'Esti sigur ca vrei sa stergi lectia?', 'Da', 'Nu', StergeLectie, dbid, object);
      })

      .on('click', '#save-titlu-lectie', function() { //Salvare denumire titlu by userinput

          var object = this;
          var nume_nou = $("#NumeLectie").val();

          var dbid = $("#NumeLectie").attr('data-dbid');

          $.ajax({
             url: 'save.php', //This is the current doc
             type: "POST", 
             data: {
              'tip_salvare': 2, //Salvare_nume_lectie
              'id' : dbid,
              'valoare': nume_nou
             },
             success: function(data){
                console.log("s-a salvat numele nou al lectiei");
                AlertSucces($(object).closest('.obiect-lista'));
             }
          });
          $('#LectieSelect option[value="'+ dbid + '"]').text(nume_nou+" (ID: "+dbid+")"); //Schimba numele si in lista //Undo denumire titlu

      })

      .on('change', '#PozaLectie', function(){

        AfiseazaPreviewPozaLectie(this); //Incarca o poza din calculator
      })

      .on('click', '#save-poza-lectie', function(){
        var object = this;
        if(poza_noua_lectie){
          $.ajax({
            url: 'save.php', //This is the current doc
            type: "POST", 
            data: {
              'tip_salvare': 3, //Salvare_imagine_lectie
              'id' : lectie_selectata,
              'valoare': poza_noua_lectie
            },
            success: function(data){
              console.log("s-a salvat noua poza a lectiei");
              AlertSucces($(object).closest('.obiect-lista'));
            }
        });
        }
        else console.log("Nu exista pova_noua"); //Salveaza poza pentru lectie
      })

      .on('click', '#undo-poza-lectie', function(){
        var object = this;
        if(poza_noua_lectie){
          $.ajax({
            url: 'save.php', //This is the current doc
            type: "POST", 
            data: {
              'tip_salvare': 3, //Salvare_imagine_lectie
              'id' : lectie_selectata,
              'valoare': poza_veche_lectie
            },
            success: function(data){
              console.log("s-a salvat vechea poza a lectiei");
              AlertSucces($(object).closest('.obiect-lista'));
            }
        });
        $('#PreviewImagineLectie').attr('src', poza_veche_lectie);
        poza_noua_lectie = "";
        }
        else console.log("Nu exista pova_noua"); //Undo poza salvata - Resalvam poza pe care o avem in backup
      });


      //------------CONTINUT CAPITOL----------------
      $("#continutCapitol")

      .on('click', '#undo-titlu-capitol', function() { //Undo denumire capitol

        var nume_vechi = $("#NumeCapitol").attr('placeholder');
        var object = this;
        $("#NumeCapitol").val(nume_vechi);
        //console.log(nume_vechi);
        var dbid = $("#NumeCapitol").attr('data-dbid');
        //console.log("dbid:" + dbid + " valoare: " + nume_vechi);
        $.ajax({
           url: 'save.php', //This is the current doc
           type: "POST", 
           data: {
            'tip_salvare': 1, //Salvare_nume_capitol
            'id' : dbid,
            'valoare': nume_vechi
           },
           success: function(data){
              console.log("s-a salvat numele vechi al capitolului");
              AlertSucces($(object).closest('.obiect-lista'));
           }
        });

        $('#CapitolSelect option[value="'+ dbid + '"]').text(nume_vechi+" (ID: "+dbid+")"); //Schimba numele si in lista
    
      })

      .on('click', '#delete-capitol', function() { //Stergere capitol

          var curentObiect = $(this).closest('.obiect-lista');
          var cur_dbid = curentObiect.attr('data-dbid');

          //StergeLectie(dbid, object);
          Confirm('Stergere capitol', 'Esti sigur ca vrei sa stergi capitolul?', 'Da', 'Nu', StergeCapitol, cur_dbid, curentObiect);
      })

      .on('click', '#new-titlu-capitol', function(){
          console.log("DA");
          var object = this;
          var nume_nou = $("#NumeNouCapitol").val();

          var dbid = $("#NumeNouCapitol").attr('data-dbid'); //-1 mereu


          $.ajax({
             url: 'save.php', //This is the current doc
             type: "POST", 
             data: {
              'tip_salvare': 1, //Salvare_nume_capitol
              'id' : dbid,
              'valoare': nume_nou,
              'extra':lectie_selectata
             },
             success: function(data){
                console.log("s-a inserat un nou capitol");
                AlertSucces($(object).closest('.obiect-lista'));
                var insert_id = parseInt(data);
                $('#CapitolSelect')
                  .append($('<option>', {
                value: insert_id,
                selected: false,
                text: nume_nou + ' (ID:'+insert_id+')'
                }));

                var capitol_inserat_obj = $('#CapitolSelect option[value="'+ insert_id + '"]');
                var text_capitol_nou_obj = $('#CapitolSelect option[value="'+ insert_id + '"]').prev();
                capitol_inserat_obj.swapWith(text_capitol_nou_obj); //Swap cu textul de adaugare capitol nou
             }
          });
          //$('#LectieSelect option[value="'+ dbid + '"]').text(nume_nou+" (ID: "+dbid+")"); //Schimba numele si in lista //Undo denumire titlu
      })

      .on('change', '.obiect-lista .custom-file input', function() {

        var dbid = $(this).closest('.obiect-lista').attr('data-dbid');
        var obj =  $(this).closest('.obiect-lista').find('.form-row img');
        AfiseazaPreviewImagineObiect(this, dbid, obj);

      })

      .on('click', '#save-titlu-capitol', function() { //Salveaza denumire capitol

        var nume_nou = $("#NumeCapitol").val();
        var object = this;
        var dbid = $("#NumeCapitol").attr('data-dbid');
        console.log("dbid:" + dbid + " valoare: " + nume_nou);
        $.ajax({
           url: 'save.php', //This is the current doc
           type: "POST", 
           data: {
            'tip_salvare': 1, //Salvare_nume_capitol
            'id' : dbid,
            'valoare': nume_nou
           },
           success: function(data){
              console.log("s-a salvat");
              AlertSucces($(object).closest('.obiect-lista'));
           }
        });

        $('#CapitolSelect option[value="'+ dbid + '"]').text(nume_nou+" (ID: "+dbid+")"); //Schimba numele si in lista

       }) 

      .on('click', '#save-obiect-data', function() { //Salveaza noile informatii ale unui element/obiect-icons

        var dbid = $(this).closest('.obiect-lista').attr('data-dbid');
        var object = this;
        var text, text1, text2;
        var type = $(this).closest('.obiect-lista').attr('data-type');
        var extra_data;
        type = parseInt(type);
        //console.log('"'+type+'"');

        switch(type) {
          case 5: //__TABEL_STIL_CARD__
          
            text1 = $(this).closest('.obiect-lista').find('textarea:eq( 0 )').val();
            text2 = $(this).closest('.obiect-lista').find('textarea:eq( 1 )').val();
            //console.log(text1);
            //console.log(text2);
            text = "<title>"+text1+"</title><body>"+text2+"</body>";
            break;

          case 3: //__IMAGINE__

            for(var i = 0; i < images.length; i++){
              if(images[i].dbid == dbid){
                text = images[i].img_cod_nou;
              }
            }

            extra_data = lectie_selectata + "," + capitol_selectat;
            //console.log(extra_data);
            break;

          default:

            text = $(this).closest('.obiect-lista').find('textarea').val();
            break;
        }
        
        text = text.replace(new RegExp('<math>', 'g'), '\\('); //Pentru copy paste de pe alte site-uri :D
        text = text.replace(new RegExp('</math>', 'g'), '\\)');
        $(this).closest('.obiect-lista').find('textarea').val(text);
        console.log(text);

        var portfolio_modal = $(this).closest('.obiect-lista').find('.portfolio-modal');
        if(portfolio_modal.length){ //Exista modal

          var modal_text = portfolio_modal.find('.text'); //Div-ul de baza
          
          switch(type) {
              case 1: //__TEXT__

                  var el = modal_text.find('.content');
                  el.empty();
                  el.html(text);
                //el.text(text);

                break;
              case 4://__ELEMENT_LISTA__
                  
                  var el = modal_text.find('p');
                  el.empty();
                  el.text(text);

                  break;
              case 2: //__GRAFIC__

                  for(var i = 0; i < calculators.length; i++){
                    if(calculators[i].dbid == dbid){
                      calculators[i].desmos.setExpression({id:"graph1", latex:text});
                    }
                  }

                  break;

              case 3: //__IMAGINE__
              
                  var el = modal_text.find('img');
                  el.attr('src', text);
                  break; 

              case 5: //__TABEL_STIL_CARD__
                  
                  var el = modal_text.find('.card-body .card-title');
                  el.text(text1);

                  el = modal_text.find('.card-body .card-text');
                  el.html(text2);
                  break; 

              default:

                break;
                   
          }

          MathJax.Hub.Queue(["Typeset",MathJax.Hub]);
        }

        //console.log(text);

        $.ajax({
           url: 'save.php', //This is the current doc
           type: "POST", 
           data: {
            'tip_salvare': 4, //Salvare_continut_obiect_capitol
            'id' : dbid,
            'valoare': text,
            'componenta': type,
            'extra': extra_data
           },
           success: function(data){
              console.log("s-a salvat");
              AlertSucces($(object).closest('.obiect-lista'));
           }
        });

      })

      .on('click', '#undo-obiect-data', function() { //Revine la vechile informatii ale unui element/obiect

        var dbid = $(this).closest('.obiect-lista').attr('data-dbid');
        var i;
        var text_vechi;
        for(i = 0; i < capitol_data.length; i++){
          if(capitol_data[i]['dbid'] == dbid){
            text_vechi = capitol_data[i]['text'];
          }
        }
        //Nu mai salveaza cand dai undo.
        /*$.ajax({
           url: 'save.php', //This is the current doc
           type: "POST", 
           data: {
            'tip_salvare': 4, //Salvare_continut_obiect_capitol
            'id' : dbid,
            'valoare': text_vechi,
            'componenta': 1 //__TEXT__
           },
           success: function(data){
              console.log("s-a salvat");
              //alert("s-a salvat");
           }
        });*/

        var type = $(this).closest('.obiect-lista').attr('data-type');
        type = parseInt(type);

        //text_vechi = text_vechi.replace(/\s+/g,' ').trim();

        switch(type) {
          case 5: //__TABEL_STIL_CARD__
            //Extragem titlul si continutul din tag-uri
            var result1 = text_vechi.match(/<title>[\s\S]*?<\/title>/g).map(function(val){
               return val.replace(/<\/?title>/g,'');
            });

            //console.log(result1[0]);
            var result2 = text_vechi.match(/<body>[\s\S]*?<\/body>/g).map(function(val){
               return val.replace(/<\/?body>/g,'');
            });

            //console.log(result2[0]);

            $(this).closest('.obiect-lista').find('textarea:eq( 0 )').val(result1);
            $(this).closest('.obiect-lista').find('textarea:eq( 1 )').val(result2);
            break;

          case 3: //__IMAGINE__

            for(var i = 0; i < images.length; i++){
              if(images[i].dbid == dbid){
                text_vechi = images[i].img_cod_default;
                images[i].img_cod_nou = text_vechi;
                //console.log(text_vechi);
              }
            }
            $(this).closest('.obiect-lista').find('.form-row img').attr('src', text_vechi);

            break;

          default:

            $(this).closest('.obiect-lista').find('textarea').val(text_vechi);
            break;
        }

        //
        
      })

      .on('click', '#move-up-obiect', function() { //Muta pozitia unui obiect mai sus in lista (Schimba order in database :D)

        var curentObiect = $(this).closest('.obiect-lista');
        var cur_dbid = curentObiect.attr('data-dbid');
        var prevObiect = $(this).closest('.obiect-lista').prev();
        var prev_dbid = prevObiect.attr('data-dbid');
        //prevObiect.css("background-color", "red");
        curentObiect.swapWith(prevObiect); //Blanaoooo

        //Schimbam nebuniile din pagina acum
        curentObiect.find(".obiect-icons > .under-obiect > .move-down-icon").show(); //Ii bagam inapoi sageata de mutat in jos daca era ultimul element
        prevObiect.find(".obiect-icons > .under-obiect > .move-up-icon").show();//Ii bagam sageata sus daca elementul cu care schimbi era primul
        
        var cur_index, prev_index;

        for(var i = 0; i < capitol_data.length; i++){ //Gasim indexul celor 2 elemente
          if(capitol_data[i]['dbid'] == cur_dbid){
            cur_index = i;
          }
          if(capitol_data[i]['dbid'] == prev_dbid){
            prev_index = i;
          }
        }

        //Schimbam numarul ordinii in variabilele locale

        var aux;
        aux = capitol_data[cur_index]['order'];
        capitol_data[cur_index]['order'] = capitol_data[prev_index]['order'];
        capitol_data[prev_index]['order'] = aux;

        //Actualizam div-ul de informatii

        var cur_info_text = "DBID: "+cur_dbid+" Order: "+capitol_data[cur_index]['order'];
        curentObiect.find(".informatii").text(cur_info_text);

        var prev_info_text = "DBID: "+prev_dbid+" Order: "+capitol_data[prev_index]['order'];
        prevObiect.find(".informatii").text(prev_info_text);

        //Anulam butonul de mutat sus/jos pentru primul/ultimul element
        $('#continutCapitol > .obiect-lista[data-dbid]').last().find('.obiect-icons > .under-obiect > .move-down-icon').hide();
        $('#continutCapitol > .obiect-lista[data-dbid]').first().find('.obiect-icons > .under-obiect > .move-up-icon').hide();

        //Salvam si in baza de date
        $.ajax({
           url: 'save.php', //
           type: "POST", 
           data: {
            'tip_salvare': 5, //Salvare_nume_capitol
            'id' : prev_dbid,
            'valoare': capitol_data[prev_index]['order'],
            'componenta': 1 //__TEXT__
           },
           success: function(data){
              console.log("s-a salvat");
              AlertSucces($(curentObiect).closest('.obiect-lista'));
              //alert("s-a salvat");
           }
        });

        $.ajax({
           url: 'save.php', //
           type: "POST", 
           data: {
            'tip_salvare': 5, //Salvare_nume_capitol
            'id' : cur_dbid,
            'valoare': capitol_data[cur_index]['order'],
            'componenta': 1 //__TEXT__
           },
           success: function(data){
              console.log("s-a salvat");
              AlertSucces($(curentObiect).closest('.obiect-lista'));
              //alert("s-a salvat");
           }
        });

       }) 

      .on('click', '#move-down-obiect', function() { //Muta pozitia unui obiect mai jos in lista (Schimba order in database :D)

        var curentObiect = $(this).closest('.obiect-lista');
        var cur_dbid = curentObiect.attr('data-dbid');
        var nextObiect = $(this).closest('.obiect-lista').next();
        var next_dbid = nextObiect.attr('data-dbid');
        //nextObiect.css("background-color", "red");
        curentObiect.swapWith(nextObiect); //Blanaoooo

        //Schimbam nebuniile din pagina acum
        curentObiect.find(".obiect-icons > .under-obiect > .move-up-icon").show(); //Ii bagam inapoi sageata de mutat in sus daca era primul element
        nextObiect.find(".obiect-icons > .under-obiect > .move-down-icon").show(); //Ii bagam sageata jos daca elementul cu care schimbi era ultimul
        
        var cur_index, next_index;

        for(var i = 0; i < capitol_data.length; i++){ //Gasim indexul celor 2 elemente
          if(capitol_data[i]['dbid'] == cur_dbid){
            cur_index = i;
          }
          if(capitol_data[i]['dbid'] == next_dbid){
            next_index = i;
          }
        }

        //Schimbam numarul ordinii in variabilele locale

        var aux;
        aux = capitol_data[cur_index]['order'];
        capitol_data[cur_index]['order'] = capitol_data[next_index]['order'];
        capitol_data[next_index]['order'] = aux;

        //Actualizam div-ul de informatii

        var cur_info_text = "DBID: "+cur_dbid+" Order: "+capitol_data[cur_index]['order'];
        curentObiect.find(".informatii").text(cur_info_text);

        var next_info_text = "DBID: "+next_dbid+" Order: "+capitol_data[next_index]['order'];
        nextObiect.find(".informatii").text(next_info_text);

        //Anulam butonul de mutat sus/jos pentru primul/ultimul element
        $('#continutCapitol > .obiect-lista[data-dbid]').last().find('.obiect-icons > .under-obiect > .move-down-icon').hide();
        $('#continutCapitol > .obiect-lista[data-dbid]').first().find('.obiect-icons > .under-obiect > .move-up-icon').hide();

        //Salvam si in baza de date
        $.ajax({
           url: 'save.php', //
           type: "POST", 
           data: {
            'tip_salvare': 5, //Salvare_nume_capitol
            'id' : next_dbid,
            'valoare': capitol_data[next_index]['order'],
            'componenta': 1 //__TEXT__
           },
           success: function(data){
              console.log("s-a salvat");
              AlertSucces($(curentObiect).closest('.obiect-lista'));
              //alert("s-a salvat");
           }
        });

        $.ajax({
           url: 'save.php', //
           type: "POST", 
           data: {
            'tip_salvare': 5, //Salvare_nume_capitol
            'id' : cur_dbid,
            'valoare': capitol_data[cur_index]['order'],
            'componenta': 1 //__TEXT__
           },
           success: function(data){
              console.log("s-a salvat");
              AlertSucces($(curentObiect).closest('.obiect-lista'));
              //alert("s-a salvat");
           }
        });

        
      })

      .on('click', '#delete-obiect', function(){ //Stergere obiect

        var curentObiect = $(this).closest('.obiect-lista');
        var cur_capitol = curentObiect.attr('data-dbid');

        Confirm('Stergere element', 'Esti sigur ca vrei sa stergi elementul?', 'Da', 'Nu', StergeObiect, cur_capitol, curentObiect);

      })

      .on('click', '#cancel-obiect', function(){ //Anulare inserare obiect nou

        var curentObiect = $(this).closest('.obiect-lista');
        var cur_dbid = curentObiect.attr('data-dbid');

        var html = 
        '<div class="form-row">\
            <label for="NumeAdaugareCapitol">Doresti sa adaugi un nou element?</label>\
            <div class="input-group">\
              <button type="button" class="btn btn-success" id="new-obiect" >Adauga element</button>\
            </div>\
         </div>';

        $(this).tooltip('dispose'); //Bug - ramanea textul deasupra
        curentObiect.empty();
        curentObiect.html(html);
      })

      .on('click', '#save-new-obiect', function(){ //Salvare inserare obiect nou

        var curentObiect = $(this).closest('.obiect-lista');
        var cur_dbid = curentObiect.attr('data-dbid');
        var cur_capitol = curentObiect.attr('data-capitol');

        var html = 
        '<div class="form-row">\
            <label for="NumeAdaugareCapitol">Doresti sa adaugi un nou element?</label>\
            <div class="input-group">\
              <button type="button" class="btn btn-success" id="new-obiect" >Adauga element</button>\
            </div>\
         </div>';

        var text, text1, text2;
        var type = $(this).attr('data-type');
        var extra_data;
        type = parseInt(type);
        console.log(type);

        $(this).tooltip('dispose'); //Bug - ramanea textul deasupra

        switch(type) {
          case 5: //__TABEL_STIL_CARD__
          
            text1 = $(this).closest('.obiect-lista').find('textarea:eq( 0 )').val();
            text2 = $(this).closest('.obiect-lista').find('textarea:eq( 1 )').val();
            //console.log(text1);
            //console.log(text2);
            text = "<title>"+text1+"</title><body>"+text2+"</body>";
            break;

          case 3: //__IMAGINE__

            text = poza_noua_element;

            //console.log(extra_data);
            break;

          default:

            text = $(this).closest('.obiect-lista').find('textarea').val();
            break;
        }
        
        text = text.replace(new RegExp('<math>', 'g'), '\\('); //Pentru copy paste de pe alte site-uri :D
        text = text.replace(new RegExp('</math>', 'g'), '\\)');
        $(this).closest('.obiect-lista').find('textarea').val(text);
        extra_data = lectie_selectata + "," + capitol_selectat;

        $.ajax({
           url: 'save.php', //This is the current doc
           type: "POST", 
           data: {
            'tip_salvare': 4, //Salvare_continut_obiect_capitol
            'id' : -1, //Inserare obiect
            'valoare': text,
            'componenta': type,
            'extra': extra_data
           },
           success: function(data){
              console.log("s-a salvat");
              AlertSucces($(curentObiect).closest('.obiect-lista'));
              var html_insert;

              //Refresh la continut
              $('#continutCapitol').empty();
              AfiseazaContinutul(capitol_selectat, lectie_selectata);
           }
        });

        curentObiect.empty();
        curentObiect.html(html);



      })

      .on('click', '#new-obiect', function(){ //Stergere obiect

        var curentObiect = $(this).closest('.obiect-lista');
        var capitol = curentObiect.attr('data-dbid');

        curentObiect.empty(); //Stergem continutul obiectului

        var html = 
          '<div class="form-group">\
            <label for="CategorieSelect">Alege tipul elementului</label>\
            <select class="form-control" id="CategorieSelect">\
              <option selected disabled value = "0">Click pentru a selecta</option>\
              <option value="1">Text/Explicatii (Tip:1)</option><!--Text-->\
              <option value="2">Grafic (Tip:2)</option><!--Grafic-->\
              <option value="3">Imagine (Tip:3)</option><!--Imagine-->\
              <option value="4">Chenar cu text (Formule, evidentieri etc.) (Tip:4)</option><!--Element_Lista-->\
              <option value="5">Card (Chenar cu titlu si continut) (Tip:5)</option><!--Tabel_stil_card-->\
            </select>\
          </div>';

        curentObiect.append(html);

      })

      .on('change', '#CategorieSelect', function(){ //Stergere obiect

        var curentObiect = $(this).closest('.obiect-lista');
        var capitol = curentObiect.attr('data-dbid');
        var tip = parseInt($(this).val());
        var html;

        console.log(tip);

        switch(tip){

          case 1: //__TEXT__

            html = 
            '<div class="form-row">\
              <label for="TextAreaElementNou">Insereaza textul pe care doresti sa il adaugi. Poate fi modificat mai tarziu.</label>\
              <textarea class="form-control" rows="8" value="12" id="TextAreaElementNou" placeholder="Adauga text..."></textarea>\
             </div>\
            ';

            break;

          case 2: //__GRAFIC__
          
            html = 
            '<div class="form-row">\
              <label for="TextAreaElementNou">Insereaza textul pe care doresti sa il adaugi. Poate fi modificat mai tarziu.</label>\
              <textarea class="form-control" rows="8" value="12" id="TextAreaElementNou" placeholder="Adauga text..."></textarea>\
             </div>\
            ';

            break;

          case 3: //__IMAGINE__
            var link = "/"+WEBSITE_NAME+"/img/default-lectie.png";
            html = 
            '<div class="form-row">\
                <label for="ElementNouImg">Imagine</label>\
                <div class="input-group">\
                      <div class="custom-file">\
                        <input type="file" class="form-control-file" id="ElementNouImg">\
                        <label class="custom-file-label" for="ElementNouImg">Alege imaginea</label>\
                      </div>\
                </div>\
                <div>\
                  <img style="max-width: 100%" class="img-fluid" src="'+link+'">\
                </div>\
              </div>';

              var myImage = new Image();
                myImage.onload = function(){

                poza_noua_element = getBase64Image(myImage); //Conversie poza normala in base64 pentru backup
              }
              myImage.src = link;

            break; 

          case 4: //__ELEMENT_LISTA__
          
            html = 
            '<div class="form-row">\
              <label for="TextAreaElementNou">Insereaza textul pe care doresti sa il adaugi. Poate fi modificat mai tarziu.</label>\
              <textarea class="form-control" rows="8" value="12" id="TextAreaElementNou" placeholder="Adauga text..."></textarea>\
             </div>\
            ';

            break;

          case 5: //__TABEL_STIL_CARD__
          
            html = 
              '<div class="form-row">\
                <label for="ElementNou-1">Titlu card</label>\
                <textarea class="form-control" rows="2" value="12" id="ElementNou-1" placeholder="Adauga un titlu..."></textarea>\
                <label for="ElementNou-2">Continut card</label>\
                <textarea class="form-control" rows="8" value="12" id="ElementNou-2" placeholder="Adauga text..."></textarea>\
               </div>';

            break;    

          default:
            
            html = '<div>Nu se poate adauga ceva in acest moment</div>';   
            break; 
        }

        html += 
        '<div class="obiect-icons">\
          <div class="under-obiect">\
          <i class="fas fa-plus-circle fa-2x" data-toggle="tooltip" data-placement="bottom" data-type="'+tip+'" title="Insereaza noul element" id="save-new-obiect"></i>\
          <i class="fas fa-times-circle fa-2x" data-toggle="tooltip" data-placement="bottom" title="Renunta la element" id="cancel-obiect"></i>\
          </div>\
        </div>\
        ';

        console.log(html);
        curentObiect.html(html);
        
      });

      /*.on('click', '#preview-obiect', function(){

        var curentObiect = $(this).closest('.obiect-lista');
        var cur_dbid = curentObiect.attr('data-dbid');

      });*/

  });

  function StergeObiect(dbid, object) {

    $.ajax({
       url: 'save.php', //This is the current doc
       type: "POST", 
       data: {
        'tip_salvare': 8, //Stergere_obiect
        'id' : dbid,
        'valoare': ''
       },
       success: function(data){
          object.detach();
          console.log("s-a sters obiectul");
          AlertSucces($(object).closest('.obiect-lista'));
          $('#continutCapitol > .obiect-lista > .obiect-icons> .under-obiect > .move-up-icon').first().hide(); //Nu afiseaza primul buton de mutat elementul in sus
          $('#continutCapitol > .obiect-lista > .obiect-icons> .under-obiect > .move-down-icon').last().hide(); //Nu afiseaza ultimul buton de mutat elementul in jos
       }
    });
  }

  function AfiseazaPreviewPozaLectie(input){

    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.readAsDataURL(input.files[0]);

        reader.onload = function (e) {
          poza_noua_lectie = e.target.result;
          $('#PreviewImagineLectie').attr('src', e.target.result); //Preview al imaginii

          }
      }
  }

  function AfiseazaPreviewImagineObiect(input, dbid, imgobj){

    if(dbid == -1){

      if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.readAsDataURL(input.files[0]);

        reader.onload = function (e) {
          
          poza_noua_element = e.target.result;

          imgobj.attr('src', e.target.result); //Preview al imaginii
        }
      }  
    }
    else {

      if (input.files && input.files[0]) {
        var reader = new FileReader();
        var id;
        reader.readAsDataURL(input.files[0]);

        reader.onload = function (e) {
          
          for(var i = 0; i < images.length; i++){ //Gasim ID imagine dupa dbid
            if(images[i].dbid == dbid){
              id = i;
            }
          }
          console.log("id:::", id);
          images[id].img_cod_nou  = e.target.result;

          imgobj.attr('src', e.target.result); //Preview al imaginii
        }
      }
    }

  }
  
  function StergeLectie(lectie, object) {

    $.ajax({
       url: 'save.php', //This is the current doc
       type: "POST", 
       data: {
        'tip_salvare': 6, //Stergere_lectie
        'id' : lectie,
        'valoare': ''
       },
       success: function(data){
          console.log("s-a sters lectia");
          AlertSucces($(object).closest('.obiect-lista'));
       }
    });
    $('#LectieSelect option[value="'+ lectie + '"]').detach(); //Sterge optiunea din lista
    $('#LectieSelect option[value="0"]').prop('selected', true);

    Remove_Capitole_Options();
    $('#CapitolSelect')
        .append($('<option>', {
    value: 0,
    selected: true,
    text: 'Selecteaza un capitol'
    }))
    .append($('<option>', {
    value: -1,
    selected: false,
    text: '(+) Adauga un capitol nou'
    }));
    $('#CapitolSelect option:selected').attr('disabled','disabled');

    $('#continutCapitol').empty();
    $('#continutLectie').empty();
    $('#continutCapitol').hide();
    $('#continutLectie').hide();
    $('#titlu-lectie').hide();
    $('#titlu-capitole').hide();
  }

  function StergeCapitol(capitol, object) {

    $.ajax({
       url: 'save.php', //This is the current doc
       type: "POST", 
       data: {
        'tip_salvare': 7, //Stergere_lectie
        'id' : capitol,
        'valoare': ''
       },
       success: function(data){
          console.log("s-a sters capitolul");
          AlertSucces($(object).closest('.obiect-lista'));
       }
    });
    $('#CapitolSelect option[value="'+ capitol + '"]').detach(); //Sterge optiunea din lista
    $('#CapitolSelect option[value="0"]').prop('selected', true);
  }

  function AfiseazaCapitole(lectie) {
    if(lectie == -1) { //Lectie noua

    }
    else {
        $.ajax({
         url: 'get.php', //This is the current doc
         type: "GET", 
         data: {
          'tip': 'capitole',
          'lectie' : lectie
         },
         success: function(data){
            $('#CapitolSelect')
              .append($('<option>', {
          value: 0,
          selected: true,
          text: 'Selecteaza un capitol'
          }))
          .append(data)
          .append($('<option>', {
          value: -1,
          selected: false,
          text: '(+) Adauga un capitol nou'
          }));
          $('#CapitolSelect option:selected').attr('disabled','disabled');
        
        }
      });
    }   
  }

  function AfiseazaContinutul(capitol, lectie){
      $.ajax({
        url: 'get.php', //This is the current doc
         type: "GET", 
         data: {
          'tip': 'continut',
          'lectie' : lectie,
          'capitol' : capitol
         },
         success: function(data){
          var obj = JSON.parse(data);
          $('#continutCapitol').html(obj.html);
          capitol_data = obj.data;
          console.log(capitol_data);
          $('#continutCapitol > .obiect-lista > .obiect-icons> .under-obiect > .move-up-icon').first().hide(); //Nu afiseaza primul buton de mutat elementul in sus
          $('#continutCapitol > .obiect-lista > .obiect-icons> .under-obiect > .move-down-icon').last().hide(); //Nu afiseaza ultimul buton de mutat elementul in jos
          //console.log(capitol_data);

          //Transformare cod matematica

          MathJax.Hub.Queue(["Typeset",MathJax.Hub,"#continutCapitol"]);

          // Modal popup
          $('.preview-icon').magnificPopup({
            type: 'inline',
            preloader: false,
            focus: '#username',
            modal: true
          });
          $(document).on('click', '.portfolio-modal-dismiss', function(e) {
            e.preventDefault();
            $.magnificPopup.close();
          });
          //$('#continutCapitol .obiect-lista .text #calculator-' + '14').css("background-color", "red");
          //Creere calculatoare pentru grafice
          var calcs_primite = obj.calcinfo;

          for(var i = 0; i < calcs_primite.length; i++){

            var divname = "calculator-" + calcs_primite[i]['dbid'];
            var eldiv = $('#continutCapitol .obiect-lista .text #calculator-' + calcs_primite[i]['dbid'])[0];
            console.log(eldiv);
            //eldiv.css("background-color", "red");

            /*calculators[i]['desmos'] = Desmos.GraphingCalculator(eldiv, {
              expressionsCollapsed: "true"
            });
            console.log(capitol_data[calcs_primite[i]['id']]['text']);
            calculators[i]['desmos'].setExpression({id:"graph1", latex:capitol_data[calcs_primite[i]['id']]['text']});
            calculators[i]['dbid'] = calcs_primite[i]['dbid'];*/

            calculators.push({
                desmos: Desmos.GraphingCalculator(eldiv, {
                  expressionsCollapsed: "true"
                }),
                dbid: calcs_primite[i]['dbid']
            });

            calculators[i].desmos.setExpression({id:"graph1", latex:capitol_data[calcs_primite[i]['id']]['text']});

          }

          //Imagini __IMAGINE__

          var imagini_primite = obj.imgs;

          for(var i = 0; i < imagini_primite.length; i++){
        
              images.push({
                  id: imagini_primite[i]['id'],
                  dbid: imagini_primite[i]['dbid'],
                  url_server: imagini_primite[i]['url_imagine_server'],
                  img_cod_nou: null,
                  img_cod_default: null
              });
              console.log(images[i].id);


              images[i].img_cod_default = "bla";
              var myImage = new Image();
              var index = i;

              myImage.onload = function(){

                ImagineIncarcata(this, index);
              }
              myImage.src = images[i].url_server;
          }

        }
      });
      $('#titlu-capitole').show();
      $('#continutCapitol').show();
  }

  function ImagineIncarcata(img, id){
    images[id].img_cod_default = getBase64Image(img);
    //console.log(images[id].img_cod_default);
  }

  function Remove_Capitole_Options(){
    $('#CapitolSelect')
        .empty();
  }

})(jQuery);

