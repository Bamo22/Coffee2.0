$(document).ready(function(){
    intervalTrigger();

    $('#usercontrol').click(function() {
        $('#usrlist').empty();
         $.ajax({
            url: "http://localhost/coffee2.0/_funct/ajaxHandelr.php",
            data: {'f':'gatherAllUsers'},
            type: 'POST',
            success: function(usrs) {
             try {
                var users = JSON.parse(usrs);
             } catch(e) {
                console.log('error, could not parse json.')
                console.log(usrs);
             }
                for(i = 0; i < users.length; i++){
                    $('#usrlist').append("<tr><td>"+users[i].id+"</td><td>"+users[i].user_name+"</td>"+"</td><td>"+users[i].coins+"</td></tr>");
                }
            }
        });
    });

    $('#addexpense').click(function() {
        var data = {'discrp':$('#discrp').val(), 'expense':$('#expense').val()};
         
            $.ajax({
                url: "http://localhost/coffee2.0/_funct/ajaxHandelr.php",
                data: {'f':'declareExpense', 'p':data},
                type: 'POST',  
                success: function(phpResponse) {
                  console.log(phpResponse);
                  $('#discrp').val(null);
                  $('#expense').val(null);
                  location.reload();
                }
        });
    });

    $('#createCoffeeSession').click(function() {
        var data = {'name':$('#nameSession').val(), 'maxjoins':$('#maxJoinable').val()};
        $.ajax({
            url: "http://localhost/coffee2.0/_funct/ajaxHandelr.php",
            data: {'f':'createCoffeeSession', 'p':data},
            type: 'POST',
            dataType: 'json',  
            success: function(phpResponse) {
              console.log(phpResponse);
              $('#nameSession').val(null);
              $('#maxJoinable').val(null);
              UpdateSessionList();
            }
        });
    });

    $('#join').click(function() {
        $.ajax({
            url: "http://localhost/coffee2.0/_funct/ajaxHandelr.php",
            data: {'f':'joinCoffeeSession', 'p':$('#sessionList').find(":selected").val()},
            type: 'POST',
            dataType: 'json',  
            success: function(phpResponse) {
              console.log(phpResponse);
              var joinedSession = $('#sessionList').find(":selected").val();
            }
        });
    });
    $(":file").change(function () {
        if (this.files && this.files[0]) {                

            if (check_multifile_logo(this.files[0].name)) {
                var reader = new FileReader();
                reader.onload = imageIsLoaded;
                reader.readAsDataURL(this.files[0]);
                var file_data = this.files[0];  

                var form_data = new FormData(this.files[0]);

                form_data.append("file", file_data);
                $.ajax({
                    url: "http://localhost/coffee2.0/_funct/ajaxHandelr.php?f=profilePhotoUpload",
                    data: form_data,
                    type: 'POST',
                    async: false,
                    cache: false,
                    contentType: false,
                    processData: false, 
                    success: function() {
                        alert("Changed!");
                        console.log(form_data);
                    }
                });
            } else {
                alert('We only accept JPG, JPEG, PNG, GIF and BMP files');
            }
        }
    });
   
    function imageIsLoaded(e) {
        $('#file_upload').attr('src', e.target.result);
        $('#file_upload').fadeIn();
    }
    function check_multifile_logo(file) {
        var extension = file.substr((file.lastIndexOf('.') + 1));
        if (extension == 'jpg' || extension == 'jpeg' || extension == 'gif' || extension == 'png' || extension == 'bmp') {
            return true;
        } else {
            return false;
        }
    }
});
     function UpdateSessionList(){
        $('#sessionList').empty();
        $.ajax({
            url: "http://localhost/coffee2.0/_funct/ajaxHandelr.php",
            data: {'f':'refreshCoffeeSessions'},
            type: 'POST',
            dataType: 'json',  
            success: function(sessions) {
             for(i = 0; i < sessions.length; i++){
                    $('#sessionList').append("<option value='"+sessions[i].session_name+"'>"+sessions[i].session_name+"&nbsp"+sessions[i].joins+"/"+sessions[i].max_joins+"</option>");
                } 
            }
        });
    }

    function checkPhpSession(){
         $.ajax({
            url: "http://localhost/coffee2.0/_funct/ajaxHandelr.php",
            data: {'s':'o'},
            type: 'POST',
            success: function(sessionResponse) {
                var sample = "06";
                return sample;
            }
        });
    }
    function intervalTrigger(){ 
        var interval = setInterval(function(){ UpdateSessionList() }, 5000);
        if(checkPhpSession() == '06'){
            alert("yas");
        }else{
            console.log(checkPhpSession());
            //clearInterval(interval);
        }
    }