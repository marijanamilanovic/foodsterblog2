<?php
  require_once "model/fetchPosts.php";
?>
<section class="w3l-breadcrumb py-5">
  <div class="container">
      <div class="header-section">
        <h3><?=$title?></h3>
      </div>
  </div>
</section>
<div style="margin: 8px auto; display: block; text-align:center;">
</div>
<section class="w3l-index5 w3l-blog2 py-5">
  <div class="container py-lg-3">
    <form action="<?php 
      $url = "index.php?site-page=recipe";

      if(isset($_GET['page'])){
        $url .= "&page=".$_GET['page'];
      }
      if(isset($_GET['category'])){
        $url .= "&category=".$_GET['category'];
      }
      echo $url;
    ?>" method="get">
      <input type="search" name="search" placeholder="Search..."
      <?php
        if(isset($_GET['search'])):
      ?>
      value="<?=$_GET['search']?>"
      <?php
        endif;
      ?>
      />
      <?php
        if(isset($_GET['page'])):
          $page = 1;
      ?>
      <input type="hidden" name="page" value="<?=$page?>"/>
      <?php
        endif;

        if(isset($_GET['category'])):
      ?>
      <input type="hidden" name="category" value="<?=$_GET['category']?>"/>
      <?php
        endif;
      ?>
      <button type="submit" id="btnSearch"><i class="fas fa-search"></i></button>
    </form>
    <div class="row abouthy-img-grids">
      <?php
        if($mess):
      ?>
      <h2><?=$mess?></h2>
      <?php
        else:
          foreach($data as $d):
      ?>
      <div class="col-lg-6 col-md-6 blog2-posts">
        <a href="index.php?site-page=recipe&id=<?=$d->idPost?>" data-id=<?=$d->idPost?> class="blog-article-post d-block p-0">
          <div class="blog-post d-flex flex-wrap align-content-between p-4">
            <img src="assets/images/<?=$d->imgSrc?>" alt="<?=$d->name?>" class="img-fluid mb-4"/>
            <div class="post-content">
              <h4 class="blog_post_title"><?=$d->name?></h4>
              <p class="mt-3"><?=$d->openingText?></p>
            </div>
            <div class="read-button mt-5">Read whole recipe <span class="fa fa-long-arrow-right" aria-hidden="true"></span></div>
          </div>
        </a>
      </div>
      <?php
        endforeach;
        endif;
      ?>
    </div>
    <?php
      if(!$mess):
    ?>
    <div class="pagination-wrapper mt-5 text-center d-flex justify-content-center">
      <?php
        if(isset($_GET['page'])){
          $current = $_GET['page'];
        }
        else{
          $current = 1;
        }

        $next = $current+1;
        $previous = $current-1;

        if($current == $numberOfPages && $numberOfPages != 1):
      ?>
      <ul class="page-pagination">
        <li><a class="previous" href="?site-page=recipes<?php
          $addition = "";
          if(isset($_GET['category'])){
            $addition .= '&category='.$_GET['category'];
          }
          if(isset($_GET['search'])){
            $addition .= '&search='.$_GET['search'];
          }
          echo "&page=".$previous.$addition;
        ?>"><span class="fa fa-angle-left"></span></a></li>
        <?php
          $count=0;
          for($i=0; $i<$numberOfPages; $i++):
            $count=$i+1;
        ?>
        <li><a class="page-numbers" href="?site-page=recipes<?php
          $addition = "";
          if(isset($_GET['category'])){
            $addition .= '&category='.$_GET['category'];
          }
          if(isset($_GET['search'])){
            $addition .= '&search='.$_GET['search'];
          }
          echo "&page=".$count.$addition;
        ?>"
        <?php
          if($count==$current){
            echo "class='current'";
          }
        ?>><?=$i+1?></a></li>
        <?php
          endfor;
        ?>
      </ul>
        <?php
          elseif($current == 1 && $numberOfPages != 1):
        ?>
      <ul class="page-pagination">
        <?php
          $count=0;
          for($i=0; $i<$numberOfPages; $i++):
            $count=$i+1;
        ?>
        <li><a class="page-numbers" href="?site-page=recipes<?php
          $addition = "";
          if(isset($_GET['category'])){
            $addition .= '&category='.$_GET['category'];
          }
          if(isset($_GET['search'])){
            $addition .= '&search='.$_GET['search'];
          }
          echo "&page=".$count.$addition;
        ?>"
        <?php
          if($count==$current){
            echo "class='current'";
          }
        ?>><?=$i+1?></a></li>
        <?php
          endfor;
        ?>
        <li><a class="next" href="?site-page=recipes<?php
          $addition = "";
          if(isset($_GET['category'])){
            $addition .= '&category='.$_GET['category'];
          }
          if(isset($_GET['search'])){
            $addition .= '&search='.$_GET['search'];
          }
          echo "&page=".$next.$addition;
        ?>"><span class="fa fa-angle-right"></span></a></li>
      </ul>
        <?php
          elseif($current != 1):
        ?>
      <ul class="page-pagination">
        <li><a class="previous" href="?site-page=recipes<?php
          $addition = "";
          if(isset($_GET['category'])){
            $addition .= '&category='.$_GET['category'];
          }
          if(isset($_GET['search'])){
            $addition .= '&search='.$_GET['search'];
          }
          echo "&page=".$previous.$addition;
        ?>"><span class="fa fa-angle-left"></span></a></li>

        <?php
          $count=0;
          for($i=0; $i<$numberOfPages; $i++):
            $count=$i+1;
        ?>
        <li><a class="page-numbers" href="?site-page=recipes<?php
          $addition = "";
          if(isset($_GET['category'])){
            $addition .= '&category='.$_GET['category'];
          }
          if(isset($_GET['search'])){
            $addition .= '&search='.$_GET['search'];
          }
          echo "&page=".$count.$addition;
        ?>"
        <?php
          if($count==$current){
            echo "class='current'";
          }
        ?>><?=$i+1?></a></li>
        <?php
          endfor;
        ?>

        <li><a class="next" href="?site-page=recipes<?php
          $addition = "";
          if(isset($_GET['category'])){
            $addition .= '&category='.$_GET['category'];
          }
          if(isset($_GET['search'])){
            $addition .= '&search='.$_GET['search'];
          }
          echo "&page=".$next.$addition;
        ?>"><span class="fa fa-angle-right"></span></a></li>
      </ul>
        <?php
          endif;
        ?>

        <?php
          if($numberOfPages==1):
        ?>
      <ul class="page-pagination">
        <?php
          $count=0;
          for($i=0; $i<$numberOfPages; $i++):
            $count=$i+1;
        ?>
        <li><a class="page-numbers" href="?site-page=recipes<?php
          $addition = "";
          if(isset($_GET['category'])){
            $addition .= '&category='.$_GET['category'];
          }
          if(isset($_GET['search'])){
            $addition .= '&search='.$_GET['search'];
          }
          echo "&page=".$count.$addition;
        ?>"
        <?php
          if($count==$current){
            echo "class='current'";
          }
        ?>><?=$i+1?></a></li>
        <?php
          endfor;
        ?>
      </ul>
      <?php
          endif;
      ?>
    </div>
    <?php
      endif;
    ?>
  </div>
</section>
<div style="margin: 8px auto; display: block; text-align:center;">
</div>