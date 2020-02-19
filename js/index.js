$(function () {

  //recuperation du fomulaire
  const loging = $('#login-form');

  loging.on('submit', function (e) {

    e.preventDefault();
    login();

  });

  //function to execute onece the form is submit
  function login() {

    const userName = $('#user-name').val();
    const password = $('#user-password').val();

    $.ajax({

      method: 'GET',
      url: 'http://instankwick.com/Signin/' + userName + '/' + password,
      dataType: "json",

      success: function(data){

        if (data.status == "ok" || data.status  == 'allready connected'){
          
          let id = data.id;
          let token = data.token;

          window.sessionStorage.setItem('token',token);
          window.sessionStorage.setItem('id', id);
          window.sessionStorage.setItem('username', userName);
          
          document.location.href="accueil.html";

        }
        else
        document.location.href="register.html";
      }

    });

  }

});