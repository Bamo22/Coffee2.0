$(":file").change(function(){if(this.files&&this.files[0]){if(check_multifile_logo(this.files[0].name)){var a=new FileReader();a.onload=imageIsLoaded;console.log(this.files[0]);a.readAsDataURL(this.files[0]);var b=this.files[0];var c=new FormData(this.files[0]);c.append("file",b);console.log(c);$.ajax({url:"http://localhost/coffee2.0/_funct/upload.php",data:c,type:"POST",async:false,cache:false,contentType:false,processData:false,success:function(){alert("Changed!")}})}else{alert("We only accept JPG, JPEG, PNG, GIF and BMP files")}}});function imageIsLoaded(a){$("#file_upload").attr("src",a.target.result);$("#file_upload").fadeIn()}function check_multifile_logo(a){var b=a.substr((a.lastIndexOf(".")+1));if(b=="jpg"||b=="jpeg"||b=="gif"||b=="png"||b=="bmp"){return true}else{return false}};