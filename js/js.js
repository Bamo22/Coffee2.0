if($("#profileImage").length) {

  document.querySelector('#profileImage').addEventListener('change', function(e) {

    var fr = new FileReader;

    var isValid = true;

    fr.onload = function() {
      var img = new Image;
      img.onload = function() {
        if(img.width != 128 || img.height != 128) {
          $('#pictureModal').closeModal();
          alert('Image needs dimensions of 128x128!');
          isValid = false;
        }
      };
      img.src = fr.result;
    };

    var file = this.files[0];

    if(this.files[0].size > 1048576) {
      
      alert('The image may not be larger than 1MB!');
      return;
    }

    fr.readAsDataURL(file);

    var fd = new FormData();
    fd.append("profilePicture", file);


    var xhr = new XMLHttpRequest();
    xhr.open('POST', 'profilePicture', true);

    xhr.upload.onprogress = function(e) {
      if(e.lengthComputable) {
        var percentComplete = (e.loaded / e.total) * 100;
        console.log(percentComplete + '% uploaded');
        }
    };

    xhr.onload = function() {
      $('#pictureModal').closeModal();
      if(this.status == 200) {
        $('.modal-content').stopLoadingAnimation();
        var JSONResponse = GrouPlaylist.getJSONResponse(this.response);
        if(JSONResponse.code == 1) {
          $("#profileImageField").val("");
          toast('succesfully updated profile picture.',2000);
          $("#currentImage").attr('src','profile_images/'+JSONResponse.filename);
          $('.profile-image').attr('src','profile_images/' + JSONResponse.filename);
        }
        else {
          toast(JSONResponse.msg, JSONResponse.showTime);
        }
      };
    };
    xhr.send(fd);
  }, false);
}
// $(":file").change(function () {
//     //var file_id = e.target.id;
//     if (this.files && this.files[0]) {                

//             var reader = new FileReader();
//             reader.onload = imageIsLoaded;
//             reader.readAsDataURL(this.files[0]);
//         //if (check_multifile_logo($("#" + file_id).prop("files"))) {
//             $.post('../_funct/upload',{this.files[0]})
//             $.ajax({
//                 url: "../_funct/upload.php",
//                  data: this.files[0],
//                 type: 'post',
//                 success: function(data) {
//                     // display image
//                     alert('wow');
//                 }
//             });
//         //} else {
//             $("#" + html_div).html('');
//             alert('We only accept JPG, JPEG, PNG, GIF and BMP files');
//         //}
//     }
// });

// function imageIsLoaded(e) {
//     $('#file_upload').attr('src', e.target.result);
//     $('#file_upload').fadeIn();
// };
// function check_multifile_logo(file) {
//     var extension = file.substr((file.lastIndexOf('.') + 1))
//     if (extension == 'jpg' || extension == 'jpeg' || extension == 'gif' || extension == 'png' || extension == 'bmp') {
//         return true;
//     } else {
//         return false;
//     }
// }