let id_1 = null;
let user1 = null;

let token = window.sessionStorage.getItem('token');
let id = window.sessionStorage.getItem('id');
let username = window.sessionStorage.getItem('username');

class Contact{

    constructor(ParentElmt, id_2, user2){

        this.id = id_2;
        this.user = user2;
    
        this.elmt = document.createElement('li');
        this.elmt_1 = document.createElement('div');
        this.elmt_2 = document.createElement('div');
        this.elmt_3 = document.createElement('div');
        const span1 = document.createElement('span');
        const span2 = document.createElement('span');
        const p = document.createElement('p');
      
        var textnode = document.createTextNode(user2);
        var pnode = document.createTextNode('online');
      
        this.elmt_1.className = 'd-flex bd-highlight';
        this.elmt_2.className = 'img_cont';
        this.elmt_3.className = 'user_info';
        span1.className = 'online_icon';
      
        span2.appendChild(textnode);
        p.appendChild(pnode);
      
        this.elmt_3.appendChild(span2);
        this.elmt_3.appendChild(p);
        this.elmt_2.appendChild(span1)
        this.elmt_1.appendChild(this.elmt_2);
        this.elmt_1.appendChild(this.elmt_3);
        this.elmt.appendChild(this.elmt_1);

        ParentElmt.appendChild(this.elmt);
      
        if(username === this.user){
            this.elmt.className = 'active';
        }

        this.elmt.addEventListener('click', () => {
            id_1 = this.id;
            user1 = this.user;
            listpost(id, id_1, user1); 
        });

    }

}

function listpost(id, id_1, user1){
    
    $.ajax({

        method: 'GET',
        url: 'http://instankwick.com/ListPost/'+id+'/'+token+'/'+id_1,
        dataType: "json",

        success: function(data){

            document.getElementById('user_message').innerHTML = '';

            if(data.status == 'ok'){
               
                if(id == id_1){
                    document.getElementById('formpost').style.visibility = "hidden";
                }
                else{

                    let messages = data.messages;

                    for(var i = 0; i < messages.length; i++){

                        const div1 = document.createElement('div');
                        const div2 = document.createElement('div');
                        const div3 = document.createElement('div');
                        const span = document.createElement('span');

                        var textnode = document.createTextNode(messages[i][2]);
                        var time = document.createTextNode(messages[i][3]);

                        if(id === messages[i][0]){

                            var pseudonode = document.createTextNode('Moi :');

                            div1.className = 'd-flex justify-content-end mb-4';
                            div3.className = 'msg_cotainer_send';
                            span.className = 'msg_time_send';

                        }
                        else{

                            var pseudonode = document.createTextNode(user1+' :');
                            div1.className = 'd-flex justify-content-start mb-4';
                            div3.className = 'msg_cotainer';
                            span.className = 'msg_time';

                        }

                        div2.className = 'img_cont_msg';

                        div3.appendChild(div2);
                        div3.appendChild(textnode);
                        div3.appendChild(span);
                        div2.appendChild(pseudonode);
                        div1.appendChild(div3);
                        span.appendChild(time);

                        document.getElementById('user_message').appendChild(div1);
                        document.getElementById('user_message').scrollTop = document.getElementById('user_message').scrollHeight;
                            
                    }
                    document.getElementById('formpost').style.visibility = "visible";
                }
            
            }

        }

    });

}
