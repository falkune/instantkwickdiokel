$(function(){

   const formpost = $('#formpost');
   const disconect = $('#disconected');
   const contactbox = document.getElementById('contact');

   
   let pseudo = document.createTextNode(username);
   document.getElementById('info_user').appendChild(pseudo);

   disconect.on('click', disconnected);

   userloged(contactbox);

   formpost.on('submit', function(e){
  
      e.preventDefault();
      postmessage(id_1, user1);
  
   });
  
   miseEnAttente();

   function miseEnAttente(){
      setInterval(listpost(id, id_1, user1), 20000);
   }

});

function userloged(contactbox){

   $.ajax({
   
      method: 'GET',
      url: 'http://instankwick.com/Loged/'+id+'/'+token,
      dataType: "json",
   
      success: function(data){
        
         if(data.status == 'ok'){
            
            let activeUsers = data.online;
            
            for (var i = 0; i < activeUsers.length; i++) {

               let id_2 = activeUsers[i][0];
               let user2 = activeUsers[i][1];

               const contact = new Contact(contactbox, id_2, user2);

            }

         }
         else{
            console.log(data);
         }
 
      }
 
   });

}


function disconnected(){

   $.ajax({

      method: 'GET',
      url: 'http://instankwick.com/Logout/'+id+'/'+token,
      dataType: "json",

      success: function(data){

         if(data.status == 'ok'){
           
            document.location.href="index.html";

         }

      }

   });

}

function postmessage(id_1, user1){

   let message = $('#posted_message').val();

   $.ajax({

      method: 'GET',
      url: 'http://instankwick.com/SendTo/'+id+'/'+id_1+'/'+message,
      dataType: "json",

      success: function(data){
         if(data.status == 'ok'){

            listpost(id, id_1, user1);
            document.getElementById('formpost').reset();

         }
      }

   });

}

