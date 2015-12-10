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
                success: function(err_php1) {
                    console.log(err_php1);
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
};
function check_multifile_logo(file) {
    var extension = file.substr((file.lastIndexOf('.') + 1));
    if (extension == 'jpg' || extension == 'jpeg' || extension == 'gif' || extension == 'png' || extension == 'bmp') {
        return true;
    } else {
        return false;
    }
}