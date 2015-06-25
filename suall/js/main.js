var urlBackend = "/backend";

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
    this.regusr = function () {
      console.log("usersuall: ");
      console.log(usersuall);
      if(this.stdinfusr == 100){
        var datos = $('#frmusrdat').serialize();
        datos += "&f=reg&uid="+this.uid+"&gender="+this.gender;
        console.log("datos: ");
        console.log(datos);

        $.getJSON(urlBackend+"/user", datos, function(data){
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
};

usersuall = new objjugsuall();

/**
 * [objsuallweb description]
 * @return {[type]} [description]
 */
function objsuallweb(){
    this.fch = new Date();
    console.log("Se instancia suallweb: "+this.fch.toString());
    //console.log("Valor inicial de ukey: "+this.ukey+" Longitud: "+this.ukey.length);
    var idcnx = null; //guarda el id de la tarea ciclica para hacer ping al servidor
    //Funcion Que conecta con el sevidor cada cierto intervalo (ping)
    this.cnxsvrsuall = function(fch){
        var datos = "id="+usersuall.uid+"&key="+usersuall.ukey;
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

};

suallweb = new objsuallweb();



$.ajaxSetup ({
    // Disable caching of AJAX responses
    cache: false
});


//FB
window.fbAsyncInit = function() {
  FB.init({
    appId      : 750521908401740,
    cookie     : true,  // enable cookies to allow the server to access
                        // the session
    xfbml      : true,  // parse social plugins on this page
    version    : 'v2.3' // use version 2.2
  });

  // Now that we've initialized the JavaScript SDK, we call
  // FB.getLoginStatus().  This function gets the state of the
  // person visiting this page and can return one of three states to
  // the callback you provide.  They can be:
  //
  // 1. Logged into your app ('connected')
  // 2. Logged into Facebook, but not your app ('not_authorized')
  // 3. Not logged into Facebook and can't tell if they are logged into
  //    your app or not.
  //
  // These three cases are handled in the callback function.

  FB.getLoginStatus(function(response) {
    console.log("llamada desde fbAsyncInit");
    initLoginCallback(response);
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

//llamada cuando se carga la sdk de FB
function initLoginCallback(response) {
  console.log('initLoginCallback');
  console.log(response);
  if (response.status === 'connected') {
    // verifUserReg();
    $('.divbtnfb').hide();
    $('.divbtnjug').show();
  } else {
    usersuall.uid = 0;
  }
}

// This is called with the results from from FB.login().
function statusChangeCallback(response) {
  console.log('statusChangeCallback');
  console.log(response);
  $('#modlogfb').modal('hide');
  $('#aceptocondiciones').attr('checked', false); // Unchecks it
  $('.logofbbw').show();
  $('.btnfblog').hide();

  if (response.status === 'connected') {
    // Logged into your app and Facebook.
    // testAPI();
    verifUserReg();
    $('.divbtnfb').hide();
    $('.divbtnjug').show();

  } else if (response.status === 'not_authorized') {
    // The person is logged into Facebook, but not your app.
    console.log(response.status);
    var moderr = '<b>No ha sido posible validar tu identidad en el servidor de Facebook.</b><br>';
    moderr += '<b>Para poder jugar tienes que aceptar la invitacion a jugar en la ventana que se te muestra.</b>';
    $('#txtmoderr').html(moderr);
    $('#moderror').modal('show');
  } else {
    // The person is not logged into Facebook, so we're not sure if
    // they are logged into this app or not.
    var moderr = '<b>No ha sido posible validar tu identidad en el servidor de Facebook.</b><br>';
    moderr += '<b>Para poder jugar tienes que tener un perfil de Facebook y haber iniciado sesión en él.</b>';
    $('#txtmoderr').html(moderr);
    $('#moderror').modal('show');

    console.log(response.status);
  }
}

// Here we run a very simple test of the Graph API after login is
// successful.  See statusChangeCallback() for when this call is made.
function testAPI() {
  console.log('Welcome!  Fetching your information.... ');
  FB.api('/me', function(response) {
    console.log('Successful login for: ' + response.name);
    console.log('link: '+response.link)
  });
}

function verifUserReg() {
  console.log('Bienvenido! Cargando tu información.... ');
  FB.api('/me?fields=id,email,name', function(response) {
    console.log('Cargada info de: ' + response.name);
    console.log(response);
    usersuall.uid = response.id;
    usersuall.email = response.email;
    usersuall.name = response.name;

    $("#frmusrdat [name='email']").val(usersuall.email);

    var datos = $('#frmusrdat').serialize();
    datos += "&f=ver&uid="+usersuall.uid;
    console.log("datos: ");
    console.log(datos);

    $.getJSON(urlBackend+"/user", datos, function(data){
      if (200 == data.std){
        console.log(data.dat[0].id_jug);
        usersuall.ukey = data.dat[0].jug_key;
        usersuall.grupo = data.dat[0].clan;
        if(!data.dat[0].clan){
          $('.modal').modal('hide');
          $('#modnewclan').modal('show');
        }
        else{
          $('.modal').modal('hide');
          $('#modresclan').modal('show');
        }
      }
      else if (20 == data.std){
        console.log(data.msg);
        // $('#modnewclan').modal('show');
        regNewUser();

      } else {
        var moderr = '<b>No ha sido posible validar tu identidad en el servidor.</b><br>';
        $('#txtmoderr').html(moderr);
        $('.modal').modal('hide');
        $('#moderror').modal('show');
      }
    });

    // $.ajax({
    //     url: urlBackend+"/user",
    //     type: 'GET',
    //     dataType: 'json',
    //     data: datos,
    //     error: function(xhr, status, error) {
    //         alert('error');
    //     },
    //     success: function(jsonp) {
    //         console.log(jsonp);
    //     }
    // });
  });
}

function regNewUser() {
  console.log('registrando usuario.... ');
  FB.api('/me?fields=id,name,email,link,gender', function(response) {
    usersuall.uid = response.id;
    usersuall.name = response.name;
    usersuall.email = response.email;
    usersuall.gender = response.gender;
    usersuall.linkfb = response.link;

    $("#frmusrdat [name='name']").val(usersuall.name);
    $("#frmusrdat [name='email']").val(usersuall.email);
    $("#frmusrdat [name='linkfb']").val(usersuall.linkfb);

    usersuall.stdinfusr += 20;
    usersuall.regusr();
  });
  FB.api('/me/picture?type=square', function(response) {
    console.log('Picture square');
    console.log(response.data.url);
    usersuall.picsqr=response.data.url;
    $("#frmusrdat [name='picsqr']").val(usersuall.picsqr);
    usersuall.stdinfusr += 20;
    usersuall.regusr();

  });
  FB.api('/me/picture?type=normal', function(response) {
    console.log('Picture normal');
    console.log(response.data.url);
    usersuall.picnrl=response.data.url;
    $("#frmusrdat [name='picnrl']").val(usersuall.picnrl);
    usersuall.stdinfusr += 20;
    usersuall.regusr();
  });
  FB.api('/me/picture?type=large', function(response) {
    console.log('Picture large');
    console.log(response.data.url);
    usersuall.piclgr=response.data.url;
    $("#frmusrdat [name='piclgr']").val(usersuall.piclgr);
    usersuall.stdinfusr += 20;
    usersuall.regusr();
  });
  FB.api('/me/picture?type=large&width=700&height=700', function(response) {
    console.log('Picture Big');
    console.log(response.data.url);
    usersuall.picbig=response.data.url;
    $("#frmusrdat [name='picbig']").val(usersuall.picbig);
    usersuall.stdinfusr += 20;
    usersuall.regusr();
  });
}



/**
 * CONTROLADORES
 */

//Controlador del boton de login
$('.btnfblog').click(function(event) {
  event.preventDefault();
  // checkLoginState();
  FB.login(function(response) {
    statusChangeCallback(response);
  }, {
    scope: 'public_profile,email,user_friends',
    return_scopes: true
  })
});

$('.divbtnjug').click(function(event) {
  event.preventDefault();
  if(usersuall.ukey == '0')
    verifUserReg();
  else if(usersuall.grupo == 0)
    $('#modnewclan').modal('show');
  else
    $('#modusrclan').modal('show');
});


$('body').on({
  mouseenter: function() {
    $( this ).find(".img-normal").hide();
    $( this ).find(".img-over").css('display','inline');
  },
  mouseleave: function() {
    $( this ).find(".img-normal").css('display','inline');
    $( this ).find(".img-over").hide();
  }
}, 'a.iconover');

$('body').on('mouseenter', ".lnk-pag", function(event) {
  event.preventDefault();
  $('.paginador img').hide();
  $('#'+$(this).attr('imgp')).show();
});

$('body').on('click', ".lnk-pag", function(event) {
  event.preventDefault();
  $('.paginador img').hide();
  $('#'+$(this).attr('imgp')).show();
});

$('body').on('click', '#aceptocondiciones', function () {
  if($(this).is(':checked')){
    $('.logofbbw').hide();
    $('.btnfblog').show();
  }
  else{
    $('.logofbbw').show();
    $('.btnfblog').hide();
  }

});


//Controlador crear/unirse grupo

$('#modnewclan').on('click', '.btn', function(event){
  event.preventDefault();
  var datos = $('#frmnewclan').serialize();
  datos += "&f="+$(this).attr('f')+"&uid="+usersuall.uid+"&ukey="+usersuall.ukey;
  console.log("datos: ");
  console.log(datos);

  $.getJSON(urlBackend+"/clan", datos, function(data){
    if (200 == data.std){
      $('.modal').modal('hide');
      $('#modusrclan').modal('show');
      usersuall.grupo = 1;
    } else if (30 == data.std || 31 == data.std){
      alert(data.msg);
    } else {
      var moderr = '<b>No ha sido posible validar tu grupo en el servidor. Por favor intentalo nuevamente mas tarde.</b><br>';
      $('#txtmoderr').html(moderr);
      $('.modal').modal('hide');
      $('#moderror').modal('show');
    }
  });
})
