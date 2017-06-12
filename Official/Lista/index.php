<?php require '/home/justdoitlist/public_html/config.php'; 
session_start();
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <title>JustDoIT</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <link id="tema" rel="stylesheet" type="text/css" href="style.css">
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet">

    <link rel="stylesheet" href="dropDatum.css">

    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.2/css/bootstrap-select.min.css">

    <!-- Latest compiled and minified JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.2/js/bootstrap-select.min.js"></script>

    <!-- (Optional) Latest compiled and minified JavaScript translation files -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.12.2/js/i18n/defaults-*.min.js"></script>
    
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-growl/1.0.0/jquery.bootstrap-growl.min.js"></script>
<link href="https://fonts.googleapis.com/css?family=Short+Stack" rel="stylesheet">


</head>

<body>

    <div id="wrapper" class="wrapper-content">
        <div id="sidebar-wrapper">
            <ul class="sidebar-nav ">
                <div class="sidebar-brand ">
                    <a href="http://justdoitlist.com/">JustDoIT</a>
                </div>
                <li id="activeG" onclick="ispisGeneral()" style="color:#16B7FF" class="list-group-item justify-content-between">

                    <h3><i class="fa fa-list" aria-hidden="true"></i>General<span id="general" class="badge badge-default badge-pill broj">0</span>

                    </h3>
                </li>
                <li id="activeP" class="list-group-item justify-content-between" onclick="ispisPrivate()">

                    <h3><i class="fa fa-user" aria-hidden="true"></i>Private<span id="private" class="badge badge-default badge-pill broj">0</span>

                    </h3>
                </li>
                <li id="activeW" onclick="ispisWork()" class="list-group-item justify-content-between">

                    <h3><i class="fa fa-briefcase" aria-hidden="true"></i>Work<span id="work" class="badge badge-default badge-pill broj">0</span>

                    </h3>
                </li>
                <li id="activeS" onclick="ispisShopping()" class="list-group-item justify-content-between">

                    <h3><i class="fa fa-shopping-cart" aria-hidden="true"></i>Shopping<span id="shopping" class="badge badge-default badge-pill broj">0</span>

                    </h3>
                </li>

                <li id="activeC" onclick="ispisChecked()" class="list-group-item justify-content-between">

                    <h3><i class="fa fa-check" aria-hidden="true"></i>Done<span id="checked" class="badge badge-default badge-pill broj">0</span>

                    </h3>
                </li>

                <li id="activeA" onclick="ispisArhiva()" class="list-group-item justify-content-between">

                    <h3><i class="fa fa-archive fax" aria-hidden="true"></i>Archive<span id="archive" class="badge badge-default badge-pill broj">0</span>

                    </h3>
                </li>
                <div onclick="printAll()" class="sidebarfut">
                <i class="fa fa-print fa-2x" aria-hidden="true"></i><span class="sakrij">Print</span></div>
               
                <div onclick="clearLS()" class="sidebarfut">
                <i class="fa fa-trash fa-2x" aria-hidden="true"></i><span class="sakrij">Clear archive</span></div>
                


            </ul>
        </div>


        <nav class="navbar navbar-default">
            <div class="container-fluid">
               <div class="row">
                <div class="navbar-header">
                    <button class="btn-menu btn btn-toggle-menu togle" type="button">
                        <i class="fa fa-bars"></i>
                     </button>
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
                          <i class="fa fa-bars"></i>
                         </button>
                </div>
                <div class="collapse navbar-collapse" id="myNavbar">

                    <ul class="nav navbar-nav navbar-right desno">
                        <li onclick="ispisModala()"><a id="brOb" href="#" data-toggle="modal" data-target="#modalNot" class="badgenot" data-badge="">Today</a> </li>
                        <li><a href="#"><?php if($_SESSION["UserName"]){printf ( $_SESSION["UserName"]);}else{
						?>
						Profil
						<?
						}
						?></a></li>
                        <li class="dropdown"><a class="dropdown-toggle" data-toggle="dropdown" href="#">Themes<span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li onclick="tema1('style.css')"><a href="#">Default</a></li>
                                <li onclick="tema2('style1.css')"><a href="#">Second</a></li>
                                <li  onclick="tema3('style2.css')"><a href="#">Third</a></li>
                            </ul>
                        </li>
                    </ul>
</div>
                </div>
            </div>
        </nav>

        <!--modal notifikacija-->

        <div class="modal fade" id="modalNot" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form action="" method="post">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            <h4 class="modal-title text-center" id="myModalLabel"></h4>

                        </div>
                        <div class="modal-body">
                            <form action="" method="post">

                                <div class="form-group">
                                   <label style="font-size:17px;" for="category"><i class="fa fa-exclamation" aria-hidden="true"></i> JustDoIT :</label>
                                    <ul id="ulToday"></ul>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!--input-->
        <div id="page-content-wrapper">
            <div class="row" style="margin-bottom:30px;margin-left:22px;">
                <div class=" col-lg-8">
                    <div class="input-group input-group-lg add-on">
                        <input type="text" class="form-control " placeholder="Add a to-do..." id="input" maxlength="71">
                        <span class="input-group-btn">
                            <button id="opcije" href="#" data-toggle="modal" data-target="#katedat" class="btn btn-default edit" type="submit" style="border-radius=0px;"><i class="glyphicon glyphicon-edit"></i></button>
                        </span>
                        
                        <span class="input-group-btn">
                       <button id="dodaj" class="btn btn-default" type="button" onclick="noviObjekt()" >
                        <span class="glyphicon glyphicon-plus"></span> Add Task</button>
                        </span>
                        
                    </div>
                </div>
            </div>
            
            <!--modal set date...kategorija-->
            <div class="modal fade" id="katedat" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-sm" role="document">
                    <div class="modal-content">
                        <form action="" method="post">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                                <h4 class="modal-title text-center" id="myModalLabel">Edit your task</h4>
                            </div>
                            <div class="modal-body" style="font-size:16px" >
                                <form class="row" action="" method="post">

                                    <div class="form-group">
                                        <!--.kategorija-->

                                        <label for="category"><i class="fa fa-book" aria-hidden="true"></i>Category:</label>
<br>
                                        <select class="selectpicker" data-width="fit">
                         <option>General</option>
                         <option>Private</option>
                         <option>Work</option>
                         <option>Shopping</option>
                        </select>
                                    </div>


                                    <!--due date-->
                                    <div class="form-group">

                                        <label for="duedate"><i class="fa fa-calendar" aria-hidden="true"></i>Set due date:</label>
                                        <input type="text" class="form-control " placeholder="Due date..." id="inputDatum" > 

                                    </div>

                                </form>
                            </div>
                            <div class="modal-footer">

                                <button type="button" class="btn btn-primary" data-dismiss="modal">Confirm</button>
                            </div>

                        </form>
                    </div>
                </div>
            </div>

            <!--taskovi-->


            <div class="container-fluid">
                <div class="row ">

                    <ul id="lista">

                    </ul>


                </div>
            </div>

        </div>

    </div>

    <script>
        $(function() {
            $(".btn-toggle-menu").click(function() {
                $("#wrapper").toggleClass("toggled");
            });
        })
    </script>
    <script src="ime.js"></script>


</body>


</html>
