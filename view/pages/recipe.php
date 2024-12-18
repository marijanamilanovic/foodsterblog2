<?php
  require_once "model/fetchPost.php";
  require_once "model/fetchCommentsRecipe.php";
?>
<section class="w3l-breadcrumb py-5">
  <div class="container">
      <div class="header-section">
        <h3><?php
          if($mess != ""){
            echo $mess;
          }
          else{
            echo $data->postName;
          }
        ?></h3> 
      </div>
  </div>
</section>
<div style="margin: 8px auto; display: block; text-align:center;">
  <!---728x90--->
</div>

<section class="w3l-blog-single py-5">
  <div class="container py-lg-3">
    <?php
        if($mess == ""):
    ?>
    <div class="row abouthy-img-grids">
      <div class="col-lg-6 col-md-6 blog2-posts">
        <div class="img-circle postRecipe">
          <img src="assets/images/<?=$data->imgSrc?>" class="img-fluid" alt="<?=$data->postName?>"/>
        </div>
      </div>
      <div class="col-lg-6 col-md-6 blog2-posts">
        <div class="message">
              <div class="single-post-content">
                <p class="mb-4"><?=$data->openingText?></p>
                <p class="mb-4"><?=$data->text?></p>             
              </div>
        </div>
      </div>
    </div>
    <div class="row mt-5">
      <div class="col-lg-8 pl-lg-0 offset-lg-3 offset-0">
        <nav class="post-navigation row mb-4 mt-lg-0 mt-5 py-4 text-left">
          <div id="rate" class="post-prev col-12 pr-sm-5">
            <?php
              if(isset($_SESSION['user'])):
            ?>
            <h4 class="side-title mb-2">Rate this post</h4>
            <?php
              else:
            ?>
            <h4 class="side-title mb-2"><a href="index.php?site-page=login" style="font-size: 28px;">Log in</a> to rate</h4>
            <?php
              endif;
            ?>
            <?php
              for($i=0; $i<5; $i++):
                if($i<$rating):
            ?>
              <a href="" data-id="<?=$i+1?>"><i class="fas fa-star" data-post="<?=$data->idPost?>" data-rating="<?=$i+1?>"></i></a>
              <?php
                else:
              ?>
              <a href="" data-id="<?=$i+1?>"><i class="far fa-star" data-post="<?=$data->idPost?>" data-rating="<?=$i+1?>"></i></a>
              <?php
                endif;
              endfor;
              ?>
              <p id="errorRating"></p>
          </div>
        </nav>
        <h4 class="side-title ">Comments</h4>
        <?php
            if($comment != ""):
              foreach($comment as $c):
          ?>
        <div class="comments mt-5">
          <div class="media mt-4">
            <div class="media-body">
              <ul class="time-rply mb-2">
                <li class="name mt-0 mb-2 d-block" style="color: #000;"><?=$c->userName?>
                  <?=$c->dateOfComment?>
                </li>
              </ul>
              <p style="color: #383838;"><?=$c->commentText?></p>
            </div>
          </div>
        </div>
        <?php
          endforeach;
        else:
        ?>
        <p>No comments yet!</p>
        <?php
          endif;
        ?>
        <div class="leave-comment-form mt-5" id="reply">
          <?php
            if(isset($_SESSION['user'])):
          ?>
          <h4 class="side-title mb-2">Leave your comment</h4>
          <form action="">
            <div class="form-group">
              <textarea name="postComment" id="postComment" class="form-control" placeholder="Your Comment" spellcheck="false"></textarea>
            </div>
            <input type="hidden" id="idPost" value="<?=$_GET['id']?>"/>
            <div class="submit text-right">
              <input type="button" value="Post Comment" id="btnPostCom" name="btnPost" class="btn btn-style btn-primary"/>
            </div>
          </form>
          <p id="messComment"></p>
          <?php
            else:
          ?>
          <h5><a href="index.php?site-page=register">Sign in</a> to post a comment!</h5>
          <?php
            endif;
          ?>
        </div>
      </div>
    </div>
    <?php
      endif;
    ?>
  </div>
</section>
<div style="margin: 8px auto; display: block; text-align:center;">
  <!---728x90--->
</div>