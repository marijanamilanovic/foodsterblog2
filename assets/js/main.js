$(document).ready(function(){
    var url = location.href;
    
    $("#btnPostCom").click(postComment);
    $("#rate i").click(rate);
    admin();

    if(url.indexOf("index.php") != -1){
        fetchLatestPosts();
        fetchCategories();
        fetchComments();
    }
})

function fetchLatestPosts(){
    $.ajax({
        url: "model/fetchLatestPosts.php",
        method: "get",
        dataType: "json",
        success: function(data){
            printLatestPosts(data);
        },
        error: function(xhr){
            $("#latestPosts").html(JSON.parse(xhr.responseText));
        }
    })
}

function printLatestPosts(data){
    let print = "";
    for(let d of data){
        print += `<div class="col-lg-4">
        <a href="index.php?site-page=recipe&id=${d.idPost}" class="blog-article-post height-full bg-color-three d-flex flex-wrap align-content-between">
          <div class="post-content">
            <h4 class="blog_post_title">${d.name}</h4>
            <p>${d.openingText}</p>
          </div>
          <div class="read-button mt-5">Read whole recipe <span class="fa fa-long-arrow-right" aria-hidden="true"></span></div>
        </a>
      </div>`;
    }
    document.getElementById("latestPosts").innerHTML += print;
}

function fetchCategories(){
    $.ajax({
        url: "model/fetchCategories.php",
        method: "get",
        dataType: "json",
        success: function(data){
            printCategories(data);
        },
        error: function(xhr){
            $("#categories").html(JSON.parse(xhr.responseText));
        }
    })
}

function printCategories(data){
    let print = "";
    data.forEach(d => {
        print += `<a href="index.php?site-page=recipes&page=1&category=${d.idCategory}" class="col-lg-4 bg-color-three mt-lg-0 mt-0">
        <div class="blog-article-post d-flex flex-wrap align-content-between">
          <div class="post-content">
            <img src="assets/images/${d.img}" alt="pasta" class="img-fluid"/>
            <h4 class="blog_post_title my-2">${d.name}</h4>
          </div>
        </div>
      </a>`;
    });
    $("#categories").html(print);
}

function fetchComments(){
    $.ajax({
        url: "model/fetchComments.php",
        method: "get",
        dataType: "json",
        success: function(data){
            printComments(data);
        },
        error: function(xhr){
            $("#comments").html(JSON.parse(xhr.responseText));
        }
    })
}

function printComments(data){
    let print = "";
    data.forEach(d => {
        print += `<div class="col-lg-6">
        <div class="slider-info">
          <div class="message">
            <h5>${d.name}</h5>
            <p>${d.text}</p>
          </div>
        </div>
      </div>`;
    });
    $("#comments").html(print);
}

$("#btnLog").click(function(){ 
    var email = document.getElementById("emailLog").value;
    var pass = document.getElementById("passLog").value;

    var reEmail = /^\w([\.-]?\w+\d*)*@\w+\.\w{2,6}$/;
    var rePass = /^.{8,50}$/;

    if(!reEmail.test(email)){
        $("#errorEmailLog").html("Invalid email. Example: paulwesley@gmail.com");            
        return false;
    }
    else{
        $("#errorEmailLog").html("");
    }

    if(!rePass.test(pass)){
        $("#errorPassLog").html("Password must contain at least 8 characters.");            
        return false;
    }
    else{
        $("#errorPassLog").html("");
    }

    $.ajax({
        url: "model/log.php",
        method: "post",
        dataType: "json",
        data: {
            "emailLog": email,
            "passLog": pass,
            "btnLog": true
        },
        success: function(data){
            if(data == "Successfully logged in!"){
                location.href = "index.php";
            }
            else{
                $("#errorBtnLog").html(data);
            }
        },
        error: function(xhr){
            $("#errorBtnLog").html(JSON.parse(xhr.responseText));
        }
    })       
})

$("#btnReg").click(function(){
    var name = document.getElementById("name").value;
    var email = document.getElementById("email").value;
    var pass = document.getElementById("passReg").value;
    var passConf = document.getElementById("passConf").value;

    var reName = /^(([A-ZČĆŽĐŠ][a-zčćžđš]{2,15})+)\s(([A-ZČĆŽĐŠ][a-zčćžđš]{2,15})+)$/;
    var reEmail = /^\w([\.-]?\w+\d*)*@\w+\.\w{2,6}$/;
    var rePass = /^.{8,50}$/;
    var error = 0;

    if(!reName.test(name)){
        $("#errorName").html("Invalid name. Example: Paul Wesley");            
        error++;
    }
    else{
        $("#errorName").html("");
    }

    if(!reEmail.test(email)){
        $("#errorEmail").html("Invalid email. Example: paulwesley@gmail.com");            
        error++;
    }
    else{
        $("#errorEmail").html("");
    }

    if(!rePass.test(pass)){
        $("#errorPass").html("Password must contain at least 8 characters.");            
        error++;
    }
    else{
        $("#errorPass").html("");
    }

    if(passConf != pass){
        $("#errorConfPass").html("Passwords don't match.");            
        error++;
    }
    else{
        $("#errorConfPass").html("");
    }

    if(error == 0){
        $.ajax({
            url: "model/registration.php",
            method: "post",
            dataType: "json",
            data: {
                "name": name,
                "email": email,
                "passReg": pass,
                "passConf": passConf,
                "btnReg": true
            },
            success: function(data){
                if(data == "There is already a user with that email address."){
                    $("#email").val("");
                    $("#errorEmail").html(data);
                }
                else{
                    $("input[type='text']").val("");
                    $("input[type='password']").val("");
                    $("#errorReg").html(data);
                }
            },
            error: function(xhr){
                if(xhr.status == 422){
                    error = JSON.parse(xhr.responseText);
                    //console.log(error);
                    if(error.errorName != ""){
                        $("#errorName").html(error.errorName);
                    }
                    if(error.errorEmail != ""){
                        $("#errorEmail").html(error.errorEmail);
                    }
                    if(error.errorPass != ""){
                        $("#errorPass").html(error.errorPass);
                    }
                    if(error.errorConfPass != ""){
                        $("#errorConfPass").html(error.errorConfPass);
                    }
                }
                else{
                    $("#errorReg").html(JSON.parse(xhr.responseText));
                }                   
            }
        })
    } 
})

function rate(){
    var rating = this.dataset.rating;
    var post = this.dataset.post;

    $.ajax({
        url: "model/rate.php",
        method: "post",
        dataType: "json",
        data: {
            "rating": rating,
            "recipe": post,
            "click": true
        },
        success: function(data){
            if(data != "Log in to rate"){
                location.reload();
            }
            $("#errorRating").html(data);
        },
        error: function(xhr){
            $("#errorRating").html(JSON.parse(xhr.responseText));
        }
    })
}

function postComment(){
    var idPost = $("#idPost").val();
    var comment = $("#postComment").val();

    comment = comment.split(" ");

    if(comment.length <= 4){
        $("#messComment").html("Comment must have 5 words minimum.");
        return false;
    }
    $.ajax({
        url: "model/postComment.php",
        method: "post",
        dataType: "json",
        data: {
            "idPost": idPost,
            "comment": comment,
            "postCom": true
        },
        success:function(data){
            if(data != "Success"){
                $("#messComment").data;
            }
            location.reload();
        },
        error:function(xhr){
            $("#messComment").html(JSON.parse(xhr.responseText));
        }
    })
}

$("#btnSend").click(function(){
    var email = document.getElementById("emailMess").value;
    var title = document.getElementById("tbTitle").value;
    var message = document.getElementById("taMessage").value;

    var reEmail = /^\w([\.-]?\w+\d*)*@\w+\.\w{2,6}$/;
    var reTitle = /^[\w\s]+$/;
    var error = 0;

    if(!reEmail.test(email)){
        $("#errorEmailMess").html("Invalid email. Example: paulwesley@gmail.com");            
        error++;
    }
    else{
        $("#errorEmailMess").html("");
    }

    if(!reTitle.test(title)){
        $("#errorTitle").html("Title is required.");            
        error++;
    }
    else{
        $("#errorTitle").html("");
    }

    message = message.split(" ");
    if(message.length < 5){
        $("#errorTextarea").html("The message must contain at least 5 words.");            
        error++;
    }
    else{
        $("#errorTextarea").html("");
    }

    if(error == 0){
        $.ajax({
            url: "model/sendMessage.php",
            method: "post",
            dataType: "json",
            data: {
                "emailMess": email,
                "tbTitle": title,
                "taMessage": message,
                "btnSend": true
            },
            success: function(data){
                $("#errorBtnSend").html(data);
                $("#emailMess").val("");
                $("#tbTitle").val("");
                $("#taMessage").val("");
            },
            error: function(xhr){
                $("#errorBtnSend").html(JSON.parse(xhr.responseText));
            }
        })
    }
})

function admin(){
    allRecipes();
    $("#adminPanel a").click(function(e){
        console.log(this.dataset.name);
        e.preventDefault();
        var choice = this.dataset.name;
        if(choice == "allRecipes"){
            allRecipes();
        }
        if(choice == "addRecipe"){
            addRecipe();
        }
        if(choice == "allCategories"){
            allCategories();
        }
        if(choice == "addCategory"){
            addCategory();
        }
        if(choice == "allMessages"){
            allMessages();
        }
        if(choice == "allComments"){
            allComments();
        }
        if(choice == "allUsers"){
            allUsers();
        }
        if(choice == "home"){
            location.href = "index.php";
        }
    }) 
}

function allRecipes(){
    $.ajax({
        url: "model/allRecipes.php",
        method: "post",
        dataType: "json",
        success: function(data){
            let print = '<h2 id="allRecipesHeading">All recipes</h2>';
            for(let d of data){
                print += `<div class='adminRecipe'>
                <div class="imgRecipe"><img src="assets/images/${d.imgSrc}" alt="recipe" style="width: 90%; border-radius: 3px;"/></div>
                <div class='textAllRecipes'>
                    <h3>${d.name}</h3>
                    <div class="ratingPosts">`
                        var rating = d.rating;
                        for(let i=0; i<5; i++){
                            if(i<rating){
                                print += `<i class="fas fa-star"></i>`;
                            }
                            else{
                                print += `<i class="far fa-star></i>`;
                            }
                        }
                    print += `</div><br/>
                    <div class="buttonsAdmin">
                        <a class="recipeDelete" href="#" data-id="${d.idPost}">Delete</a>
                        <a class="recipeUpdate" href="#" data-id="${d.idPost}">Update</a>
                    </div>
                </div>
                </div>`;
            }

            $("#recipes").html(print);
            $(".recipeDelete").click(deleteRecipe);
            $(".recipeUpdate").click(updateRecipe);
        },
        error: function(xhr){
            $("#recipes").html(JSON.parse(xhr.responseText));
        }
    })
}

function updateRecipe(e){
    e.preventDefault();
    var idPost = this.dataset.id;
    $.ajax({
        url: "model/updateRecipe.php",
        method: "post",
        dataType: "json",
        data: {
            "id": idPost,
            "button": true
        },
        success: function(data){
            let print = "";
            print += `<div class="updateDiv">
                        <h2>Update ${data['name']}</h2>
                        <div class="forImg">
                            <img id="imgUpdate" src="assets/images/${data['imgSrc']}"/>
                        </div>
                        <div id="formUpdate">
                            <form enctype="multipart/form-data">
                                <input type="file" id="imgUpdateRecipe"/><br/> 
                                <input type="hidden" id="idPr" value="${data['idPost']}"/><br/>
                                <input value="${data['name']}" type="text" placeholder="Name" id="nameUpdateRecipe"/>                                             
                                <input type="hidden" id="oldImg" value="${data['imgSrc']}"/><br/>                         
                                <textarea placeholder="Opening text" id="openingTextUpdate" rows="3">${data['openingText']}</textarea><br/>
                                <textarea placeholder="Text" id="textUpdate" rows="15">${data['text']}</textarea><br/>
                                <input type="button" value="Update" id="updateRecipe"/>
                                <div id="errorAdmin"></div>
                            </form>
                        </div>
                    </div>`;
            $("#recipes").html(print);
            $("#updateRecipe").click(update);
        },
        error: function(xhr){
            divClass.html(JSON.parse(xhr.responseText))
        }
    })
}

function update(){
    var id = document.getElementById("idPr").value;
    var title = document.getElementById("nameUpdateRecipe").value;
    var openingText = document.getElementById("openingTextUpdate").value;
    var text = document.getElementById("textUpdate").value;
    var img = document.getElementById("imgUpdateRecipe").files[0];
    var imgSrc = document.getElementById("oldImg").value;

    var dataToSend = new FormData();

    dataToSend.append("id", id);
    dataToSend.append("title", title);
    dataToSend.append("openingText", openingText);
    dataToSend.append("text", text);
    dataToSend.append("img", img);
    dataToSend.append("imgSrc", imgSrc);
    dataToSend.append("btnUpdate", true);

    $.ajax({
        url: "model/update.php",
        method: "post",
        dataType: "json",
        processData: false,
        contentType: false,
        data: dataToSend,
        success: function(data){
            $("#errorAdmin").html(data['mess']);
            // if(data['img'] == undefined){
            //     $(".forImg").html("<img src='assets/images/" + imgSrc + "' alt='" + title + "'/>");
            // }
            // else{
            //     $(".forImg").html("<img src='assets/images/" + data['img'] + "' alt='" + title + "'/>");
            // }
        },
        error: function(xhr){
            $("#errorAdmin").html(JSON.parse(xhr.responseText));
        }
    })
}

function deleteRecipe(e){
    e.preventDefault();
    var idPost = this.dataset.id;
    var divClass = $(this).parent().parent().parent();

    $.ajax({
        url: "model/deleteRecipe.php",
        method: "post",
        dataType: "json",
        data: {
            "id": idPost,
            "btnDelete": true
        },
        success: function(data){
            divClass.html("<p>"+data+"</p>");
        },
        error: function(xhr){
            divClass.html(JSON.parse(xhr.responseText));
        }
    })
}

function addRecipe(){
    $.ajax({
        url: "model/fetchCategories.php",
        method: "get",
        dataType: "json",
        success: function(cat){
            let print = "";
            print += `<div class="updateDiv">
                        <h2>Add new recipe</h2>
                        <div id="formUpdate">
                            <form enctype="multipart/form-data">
                                <input type="file" id="imgInsertRecipe"/><br/> 
                                <input type="text" placeholder="Name" id="nameUpdateRecipe"/>                                                                     
                                <textarea placeholder="Opening text" id="openingTextUpdate" rows="3"></textarea><br/>
                                <textarea placeholder="Text" id="textUpdate" rows="15" cols="15"></textarea><br/>
                                <select id="chooseCategory">
                                    <option value="0">Choose category</option>`;
                                    for(let c of cat){
                                        print += `<option value="${c.idCategory}">${c.name}</option>`;
                                    }
                                print += `</select><br/>
                                <input type="button" value="Add recipe" id="addNewRecipe"/>
                                <div id="errorAdmin"></div>
                            </form>
                        </div>
                    </div>`;

            $("#recipes").html(print);
            $("#addNewRecipe").click(addPost);
        },
        error: function(xhr){
            $("#recipes").html(JSON.parse(xhr.responseText));
        }
    })
}

function addPost(){
    var imgFile = document.getElementById("imgInsertRecipe").files[0];
    var title = document.getElementById("nameUpdateRecipe").value;
    var opText = document.getElementById("openingTextUpdate").value;
    var text = document.getElementById("textUpdate").value;
    var category = document.getElementById("chooseCategory").value;

    var dataToSend = new FormData();

    dataToSend.append("img", imgFile);
    dataToSend.append("title", title);
    dataToSend.append("openingText", opText);
    dataToSend.append("text", text);
    dataToSend.append("category", category);
    dataToSend.append("btn", true);

    $.ajax({
        url: "model/addRecipe.php",
        method: "post",
        dataType: "json",
        processData: false,
        contentType: false,
        data: dataToSend,
        success: function(data){
            $("#errorAdmin").html(data);
        },
        error:function(xhr){
            $("#errorAdmin").html(JSON.parse(xhr.responseText));
        }
    })
}

function allCategories(){
    $.ajax({
        url: "model/fetchCategories.php",
        method: "get",
        dataType: "json",
        success: function(data){
            let print = '<h2 id="allRecipesHeading">All categories</h2>';
            for(let d of data){
                print += `<div class='recipe adminRecipe'>
                            <div class="imgRecipe"><img src="assets/images/${d.img}" alt="recipe" style="width: 90%; border-radius: 3px;"/></div>
                                <div class='textAllRecipes'>
                                    <h3>${d.name}</h3>
                                    <div class="buttonsAdmin">
                                        <a class="categoryDelete" href="#" data-id="${d.idCategory}">Delete</a>
                                        <a class="categoryUpdate" href="#" data-id="${d.idCategory}">Update</a>
                                    </div>
                                </div>
                        </div>`;
            }
            $("#recipes").html(print);
            $(".categoryDelete").click(deleteCategory);
            $(".categoryUpdate").click(updateCategory);
        },
        error: function(xhr){
            $("#recipes").html(JSON.parse(xhr.responseText));
        }
    })
}

function updateCategory(e){
    e.preventDefault();
    var idCategory = this.dataset.id;
    $.ajax({
        url: "model/updateCatOnClick.php",
        method: "post",
        dataType: "json",
        data: {
            "id": idCategory,
            "button": true
        },
        success: function(data){
            let print = "";
            print += `<div class="updateDiv">
                        <h2>Update ${data['name']}</h2>
                        <div class="forImg">
                            <img id="imgUpdate" src="assets/images/${data['img']}"/>
                        </div>
                        <div id="formUpdate">
                            <form enctype="multipart/form-data">
                                <input type="file" id="imgCat"/><br/> 
                                <input type="hidden" id="idCat" value="${data['idCategory']}"/><br/>
                                <input value="${data['name']}" type="text" placeholder="Name" id="nameCat"/><br/>                                                                  
                                <input type="button" value="Update" id="updateCategory"/>
                                <div id="errorAdmin"></div>
                            </form>
                        </div>
                    </div>`;
            $("#recipes").html(print);
            $("#updateCategory").click(updateCatOnClick);
        },
        error: function(xhr){
            divClass.html(JSON.parse(xhr.responseText))
        }
    })
}

function updateCatOnClick(){
    var file = document.getElementById("imgCat").files[0];
    var title = document.getElementById("nameCat").value;
    var id = document.getElementById("idCat").value;
    var dataToSend = new FormData();

    dataToSend.append("img", file);
    dataToSend.append("name", title);
    dataToSend.append("idCategory", id);
    dataToSend.append("btnUpdate", true);

    $.ajax({
        url: "model/updateCategory.php",
        method: "post",
        data: dataToSend,
        processData: false,
        contentType: false,
        success: function(data){
            $("#errorAdmin").html(data);
        },
        error:function(xhr){
            $("#errorAdmin").html(JSON.parse(xhr.responseText));
        }
    })
}

function deleteCategory(e){
    e.preventDefault();
    var idCategory = this.dataset.id;
    var divClass = $(this).parent().parent().parent();

    $.ajax({
        url: "model/deleteCategory.php",
        method: "post",
        dataType: "json",
        data: {
            "id": idCategory,
            "btnDelete": true
        },
        success: function(data){
            divClass.html("<p>"+data+"</p>");
        },
        error: function(xhr){
            divClass.html(JSON.parse(xhr.responseText));
        }
    })
}

function addCategory(){
    let print = "";
    print += `<div class="updateDiv">
                <h2>Add new category</h2>
                <div id="formUpdate">
                    <form enctype="multipart/form-data">
                        <input type="file" id="imgInsertCategory"/><br/> 
                        <input type="text" placeholder="Name" id="nameInsertCategory"/><br/>
                        <input type="button" value="Add category" id="addNewCategory"/>
                        <div id="errorAdmin"></div>
                    </form>
                </div>
            </div>`;

    $("#recipes").html(print);
    $("#addNewCategory").click(insertCategory);
}

function insertCategory(){
    var file = document.getElementById("imgInsertCategory").files[0];
    var name =document.getElementById("nameInsertCategory").value;

    var dataToSend = new FormData();

    dataToSend.append("img", file);
    dataToSend.append("name", name);

    $.ajax({
        url: "model/addCategory.php",
        method: "post",
        processData: false,
        contentType: false,
        data: dataToSend,
        success: function(data){
            $("#errorAdmin").html(data);
        },
        error: function(xhr){
            $("#errorAdmin").html(JSON.parse(xhr.responseText));
        }
    })
}

function allMessages(){
    $.ajax({
        url: "model/fetchMessages.php",
        method: "get",
        dataType: "json",
        success: function(message){
            let print = "";
            print += `<div id="table">
                        <h2>All messages</h2>
                        <table>
                            <thead>
                                <tr>
                                    <th>Email</th>
                                    <th>Subject</th>
                                    <th>Message</th>
                                    <th>Date</th>
                                    <th>Delete</th>
                                </tr>
                            </thead>`;
            for(let m of message){
                print += `<tr>
                            <td>${m.email}</td>
                            <td>${m.title}</td>
                            <td>${m.message}</td>
                            <td>${m.date}</td>
                            <td><a class="deleteMessage" dataid="${m.idMessage}" href="#">Delete</a></td>
                        </tr>`;
            }
            print += `</table></div>`;
            $("#recipes").html(print);
            $(".deleteMessage").click(deleteMessage);
        },
        error:function(xhr){
            $(".deleteMessage").html(JSON.parse(xhr.responseText))
        }
    })
}

function deleteMessage(e){
    e.preventDefault();
    var idMessage = this.dataset.id;
    var row = $(this).parent().parent();
    $(this).parent().parent().remove();

    $.ajax({
        url: "model/deleteMessage.php",
        method: "post",
        dataType: "json",
        data: {
            "id": idMessage,
            "button": true
        },
        success:function(data){
            row.remove();
        },
        error:function(xhr){
            row.html(JSON.parse(xhr.responseText));
        }
    })
}

function allComments(){
    $.ajax({
        url: "model/fetchCommentsAdmin.php",
        method: "get",
        dataType: "json",
        success: function(comments){
            let print = "";
            print += `<div id="table">
                        <h2>All comments</h2>
                        <table>
                            <thead>
                                <tr>
                                    <th>Email</th>
                                    <th>Subject</th>
                                    <th>Comment</th>
                                    <th>Date</th>
                                    <th>Delete</th>
                                </tr>
                            </thead>`;
            for(let c of comments){
                print += `<tr>
                            <td>${c.userName}</td>
                            <td>${c.postName}</td>
                            <td>${c.text}</td>
                            <td>${c.date}</td>
                            <td><a href="#" class="deleteComment" dataid="${c.idComment}">Delete</a></td>
                        </tr>`;
            }
            print += `</table></div>`;
            $("#recipes").html(print);
            $(".deleteComment").click(deleteComment);
        },
        error:function(xhr){
            $("#recipes").html(JSON.parse(xhr.responseText));
        }
    })
}

function deleteComment(e){
    e.preventDefault();
    var idComment = this.dataset.id;
    var row = $(this).parent().parent();
    $(this).parent().parent().remove();

    $.ajax({
        url: "model/deleteComment.php",
        method: "post",
        dataType: "json",
        data: {
            "idComment": idComment,
            "button": true
        },
        success:function(data){
            row.remove();
        },
        error:function(xhr){
            row.html(JSON.parse(xhr.responseText));
        }
    })
}

function allUsers(){
    $.ajax({
        url: "model/fetchUsers.php",
        method: "get",
        dataType: "json",
        success: function(user){
            let print = "";
            print += `<div id="table">
                        <h2>All users</h2>
                        <table>
                            <thead>
                                <tr>
                                    <th>Name</th>
                                    <th>E-mail</th>
                                </tr>
                            </thead>`;
            for(let u of user){
            print += `<tr>
                        <td>${u.name}</td>
                        <td>${u.email}</td>
                    </tr>`;
            }
            print += `</table></div>`;
            $("#recipes").html(print);
        },
        error: function(xhr){
        $("#recipes").html(JSON.parse(xhr.responseText));
        }
    })
}
