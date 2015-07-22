// var urlBackend = "http://suall.puentearandaestic.com/backend/";
var urlBackend = "backend/";
/**
 * [objjugsuall description]
 * @return {[type]} [description]
 */
function objjugsuall () {
    this.name;
    this.email;
    this.gender;
    this.linkfb;
    this.picsqr;
    this.picnrl;
    this.piclgr;
    this.picbig;
    this.uid = 0;
    this.grupo = 0;
    this.ukey = '0';
    this.encuesta = 0;
    this.stdinfusr = 0;
    this.fch = new Date();

    // Funcion que registra al usuario en el backend
    this.regusr = function () {
      console.log("usersuall: ");
      console.log(usersuall);
      if(this.stdinfusr == 100){
        console.log("Registrando usuario");
        var datos = $('#frmusrdat').serialize();
        datos += "&f=reg&uid="+this.uid+"&gender="+this.gender;
        console.log("datos: ");
        console.log(datos);

        $.getJSON(urlBackend+"user/", datos, function(data){
          if (200 == data.std){
            console.log(data);
            // $('#modnewclan').modal('show');
          } else {
            var moderr = '<b>Se ha presentado un error al registrarte en el servidor. Por favor inténtalo de nuevo más tarde.</b><br>';
            $('#txtmoderr').html(moderr);
            $('.modal').modal('hide');
            $('#moderror').modal('show');
          }
        });

      }
    }

    //console.log("Valor inicial de ukey: "+this.ukey+" Longitud: "+this.ukey.length);
    var idcnx = null; //guarda el id de la tarea ciclica para hacer ping al servidor

    //Funcion Que conecta con el sevidor cada cierto intervalo (ping)
    this.cnxsvrsuall = function(fch){
        var datos = "id="+this.uid+"&key="+this.ukey;
        // $.getJSON("cnxsvrsuallweb.php", datos, function(data){
        //     console.log("Se reconecto con el servidor: "+fch.toString());
        //     console.log("std: "+data.std+" ukey: "+suallweb.ukey)
        //     if (data.std==0 /*&& suallweb.ukey!='0'*/){
        //         //console.log("recargar pagina");
        //         //window.location.reload();
        //     }else{
        //         console.log("no se recarga pagina");
        //         //aca van las funciones periodicas
        //     }
        // });
      //console.log("user key: "+suallweb.ukey);
    };
    this.oncnxsvr = function(){
        idcnx = setInterval('suallweb.cnxsvrsuall(Date())',60000);//1min->60000
    };
    this.offcnxsvr = function(){
        clearInterval(idcnx);
    };
    console.log("Se instancia usersuall: "+this.fch.toString());
};

usersuall = new objjugsuall();

$.ajaxSetup ({
    // Disable caching of AJAX responses
    cache: false
});


//Configura carga SDK de Facebook
window.fbAsyncInit = function() {
  FB.init({
    appId      : 750521908401740,
    cookie     : true,  // enable cookies to allow the server to access
                        // the session
    xfbml      : true,  // parse social plugins on this page
    version    : 'v2.3' // use version 2.2
  });

  FB.getLoginStatus(function(response) {
    console.log("llamada inicial a FB");
    initVerifStatusFB(response);
  });

};

// Load the SDK asynchronously
(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/sdk.js";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));



