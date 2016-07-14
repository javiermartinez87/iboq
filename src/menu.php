<style>
    .menu{
        width:100%;height:40px;padding:0;

        // background-color: #004284;
        background: -moz-linear-gradient(left, #FFFFFF 0%, #4C7BA9 10%, #4C7BA9 90%, #FFFFFF 100%); /* FF3.6+ */
        background: -webkit-gradient(linear, left top, right top, color-stop(0%,#FFFFFF), color-stop(10%,#4C7BA9), color-stop(90%,#4C7BA9), color-stop(100%,#FFFFFF)); /* Chrome,Safari4+ */
        background: -webkit-linear-gradient(left, #FFFFFF 0%, #4C7BA9 10%, #4C7BA9 90%, #FFFFFF 100%); /* Chrome10+,Safari5.1+ */
        background: -o-linear-gradient(left, #FFFFFF 0%, #4C7BA9 10%, #4C7BA9 90%, #FFFFFF 100%); /* Opera 11.10+ */
        background: -ms-linear-gradient(left, #FFFFFF 0%, #4C7BA9 10%, #4C7BA9 90%, #FFFFFF 100%); /* IE10+ */
        background: linear-gradient(to right, #FFFFFF 0%, #4C7BA9 10%, #4C7BA9 90%, #FFFFFF 100%); /* W3C */
    }
    .menu_contenedor0{position:absolute;width:100%;height:40px;}
    .menu_contenedor{
        width:80%;margin:auto;height:40px; position:relative;
    }
    .grupo_menu{
        width:130px;
        height:40px;
        margin-left:4px;
        background: #AAAAAA;
        position:relative;
        float:left;
        overflow:hidden;
    }

    .grupo_menu.primero{
        margin-left:0px; 
    }

    .grupo_menu:hover{
        height:auto;
    }

    .opcion_menu{
        height:40px;width:130px;position:relative;z-index:3;cursor:pointer;
        background-color:#004284;color:#FFFFFF;
        line-height:40px;padding-left:40px;font-weight:bold;

        background: -moz-linear-gradient(top, #004284 0%,#1C62A8 50%, #004284 100%); /* FF3.6+ */    
        background: -webkit-linear-gradient(top, #004284 0%, #1C62A8 50%, #004284 100%); /* Chrome10+,Safari5.1+ */
        background: -o-linear-gradient(top, #004284 0%, #1C62A8 50%,  #004284 100%); /* Opera 11.10+ */
        background: -ms-linear-gradient(top, #004284 0%, #1C62A8 50  #004284 100%); /* IE10+ */
        background: linear-gradient(to top, #004284 0%, #1C62A8 50%, #004284 100%); /* W3C */
    }

    .opcion_menu:hover{
        background-color:#86A5C5;
        background: -moz-linear-gradient(top, #4575A5 0%,#86A5C5 50%, #4575A5 100%); /* FF3.6+ */

        background: -webkit-linear-gradient(top, #4575A5 0%, #86A5C5 50%, #4575A5 100%); /* Chrome10+,Safari5.1+ */
        background: -o-linear-gradient(top, #4575A5 0%, #86A5C5 50%,  #4575A5 100%); /* Opera 11.10+ */
        background: -ms-linear-gradient(top, #4575A5 0%, #86A5C5 50  #4575A5 100%); /* IE10+ */
        background: linear-gradient(to top, #4575A5 0%, #86A5C5 50%, #4575A5 100%); /* W3C */
    }

    .menu a{
        text-decoration: none;
    }
</style>

<nav name='menu' class="navbar navbar-default" ng-controller="menu_controller as menu">
    <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">Menu</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="/inicio" >&nbsp;<span class="glyphicon glyphicon-home"></span></a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
               <!-- <li class="ppa Proyectos">
                    <a href class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Proyectos <span class="caret"></span></a>
                    
                </li>-->
                <li><a href="/proyectos/nuevo" >Adquisition</a></li>
                        <li><a href="/proyectos/ver" >Edition</a></li>
                        <li><a href="/proyectos/busqueda" >Retrieval</a></li>

                <?php
                /*if ($_SESSION['modo'] == 'pruebas') {
                    ?>
                    <li class="ppa Depuracion">
                        <a href class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Depuracion <span class="caret"></span></a>
                        <ul class="dropdown-menu" role="menu">
                            <li><a href="/depuracion/crea_excel">Crear Excel</a></li>
                            <li><a href="/depuracion/depurar">Analizar</a></li>
                            <li><a href="/depuracion/sube_sinonimos">Subir Sinónimos</a></li>
                        </ul>
                    </li>
                    <?php
                }*/
                ?>
            </ul>

            <ul class="nav navbar-nav navbar-right">
                <li class="cerrar" ><a href="/control/cerrar_sesion.php"><span class="glyphicon glyphicon-off"></span></a></li>       
            </ul>
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>
