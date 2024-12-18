<?php
    $data = "";

    if(isset($_SESSION['user'])){
        $user = $_SESSION['user'];
        if($user->idRole == 1){
            $display = 2;
        }
        else{
            $display = 1;
        }
    }
    else{
        $display = 3;
    }
?>
<header id="site-header">
	<div class="container">
		<nav class="navbar navbar-expand-lg navbar-dark stroke p-0">
			<a class="navbar-brand" href="index.php">
				<img src="assets/images/favicon.ico" alt="logo" style="height:40px;" />
			</a> 
			<h1>
				<a class="navbar-brand" href="index.php">Foodster</a>
			</h1>
			<button class="navbar-toggler  collapsed bg-gradient" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo02"
				aria-controls="navbarTogglerDemo02" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon fa icon-expand fa-bars"></span>
				<span class="navbar-toggler-icon fa icon-close fa-times"></span>
				</span>
			</button>
			<div class="collapse navbar-collapse" id="navbarTogglerDemo02">
				<ul class="navbar-nav mx-auto">
					<?php
						$query = "SELECT * FROM nav WHERE display=0";

						if($display == 3){
							//neautorizovan korisnik
							$query .= " OR display=3";
							$nav = $connection->query($query)->fetchAll();
						}
						else if($display == 2){
							//admin
							$query .= " OR display=2 OR display=1";
							$nav = $connection->query($query)->fetchAll();
						}
						else{
							//ulogovan korisnik
							$query .= " OR display=1";
							$nav = $connection->query($query)->fetchAll();
						}
					
						$query .= " ORDER BY priority";

						foreach($nav as $n):
					?>
					<li class="nav-item">
						<a class="nav-link" href="index.php?site-page=<?=$n->path?>"><?=$n->name?></a>
					</li>
					<?php
						endforeach;
					?>
				</ul>
			</div>
		</nav>
	</div>
</header>