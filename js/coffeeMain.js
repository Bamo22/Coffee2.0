$(document).ready(function(){

    $('#usercontrol').click(function() {
        $('#usrlist').empty();
         $.ajax({
            url: "http://localhost/coffee2.0/_funct/ajaxHandelr.php",
            data: {'f':'gatherAllUsers'},
            type: 'POST',
            
            success: function(usrs) {
               
               // var usrsEva = eval(usrs);
                alert(eval(toString(usrs)));
                    for(i = 0; i < usrs.length; i++){
                        $('#usrlist').append("<tr><td>"+usrsEva[i].id+"</td><td>"+usrsEva[i].user_name+"</td></tr>");
                    }

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
                    url: "http://localhost/coffee2.0/_funct/ajaxHandelr.php",
                    data: form_data,
                    type: 'POST',
                    async: false,
                    cache: false,
                    contentType: false,
                    processData: false, 
                    success: function() {
                        alert("Changed!");
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