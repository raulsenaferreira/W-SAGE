<?PHP
session_start();
 
if ( !isset($_SESSION['email']) and !isset($_SESSION['senha']) ) {
    session_destroy();
 
    unset ($_SESSION['email']);
    unset ($_SESSION['senha']);

    header('location:index.php');
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>W-SAGE | Dashboard</title>

        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
        <meta name="apple-mobile-web-app-capable" content="yes">
        
        <!-- Stylesheets -->
        <link href="css/default.css" rel="stylesheet" type="text/css" />
        <link href="css/style.css" rel="stylesheet" type="text/css" />
        <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="css/jquery-ui.min.css" rel="stylesheet" type="text/css" />
        <link href="css/AdminLTE.css" rel="stylesheet" type="text/css" />
        <link href="css/wsage.css" rel="stylesheet" type="text/css" />
        <!-- Font -->
        <link href="font-awesome-4.2.0/css/font-awesome.min.css" rel="stylesheet">
        <!-- Ionicons -->
        <link href="css/ionicons.min.css" rel="stylesheet" type="text/css" />
    </head>
    <body onload="init()" role="document" class="skin-blue">
        <!-- header logo: style can be found in header.less -->
        <header class="header">
            <a href="index.html" class="logo">
                <!-- Add the class icon to your logo image or logo icon to add the margining -->
                W-SAGE
            </a>
            <!-- Header Navbar: style can be found in header.less -->
            <nav class="navbar navbar-static-top" role="navigation">
                <!-- Sidebar toggle button-->
                <a href="#" class="navbar-btn sidebar-toggle" data-toggle="offcanvas" role="button">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </a>
                <div class="navbar-right">
                    <ul class="nav navbar-nav">
                        <!-- Messages: style can be found in dropdown.less-->
                        <li class="dropdown messages-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <i class="fa fa-envelope"></i>
                                <span class="label label-success">4</span>
                            </a>
                            <ul class="dropdown-menu">
                                <li class="header">Você tem 4 notificações</li>
                                <li>
                                    <!-- inner menu: contains the actual data -->
                                    <ul class="menu">
                                        <li><!-- start message -->
                                            <a href="#">
                                                <div class="pull-left">
                                                    <img src="imagens/avatar3.png" class="img-circle" alt="User Image"/>
                                                </div>
                                                <h4>
                                                    Suporte
                                                    <small><i class="fa fa-clock-o"></i> 5 mins</small>
                                                </h4>
                                                <p>Mensagem bla bla</p>
                                            </a>
                                        </li><!-- end message -->
                                        <li>
                                            <a href="#">
                                                <div class="pull-left">
                                                    <img src="imagens/avatar2.png" class="img-circle" alt="user image"/>
                                                </div>
                                                <h4>
                                                    Admin
                                                    <small><i class="fa fa-clock-o"></i> 2 hours</small>
                                                </h4>
                                                <p>Outra mensagem bla bla</p>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#">
                                                <div class="pull-left">
                                                    <img src="imagens/avatar.png" class="img-circle" alt="user image"/>
                                                </div>
                                                <h4>
                                                    Desenvolvedor
                                                    <small><i class="fa fa-clock-o"></i> Hoje</small>
                                                </h4>
                                                <p>Mensagem mensagem</p>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#">
                                                <div class="pull-left">
                                                    <img src="imagens/avatar2.png" class="img-circle" alt="user image"/>
                                                </div>
                                                <h4>
                                                    Zeca pimenta
                                                    <small><i class="fa fa-clock-o"></i> Ontem</small>
                                                </h4>
                                                <p>ehuaheuahe</p>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#">
                                                <div class="pull-left">
                                                    <img src="imagens/avatar.png" class="img-circle" alt="user image"/>
                                                </div>
                                                <h4>
                                                    OI
                                                    <small><i class="fa fa-clock-o"></i> 2 dias</small>
                                                </h4>
                                                <p>oi</p>
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="footer"><a href="#">Ver todas notificações</a></li>
                            </ul>
                        </li>
                        <!-- Notifications: style can be found in dropdown.less -->
                        <li class="dropdown notifications-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <i class="fa fa-warning"></i>
                                <span class="label label-warning">10</span>
                            </a>
                            <ul class="dropdown-menu">
                                <li class="header">Você tem 10 notificações</li>
                                <li>
                                    <!-- inner menu: contains the actual data -->
                                    <ul class="menu">
                                        <li>
                                            <a href="#">
                                                <i class="ion ion-ios7-people info"></i> 5 novos logs
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#">
                                                <i class="fa fa-warning danger"></i> descrição longa aqui
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#">
                                                <i class="fa fa-users warning"></i> 5 novas consultas
                                            </a>
                                        </li>

                                        <li>
                                            <a href="#">
                                                <i class="ion ion-ios7-cart success"></i> 25 consultas realizadas
                                            </a>
                                        </li>
                                        <li>
                                            <a href="#">
                                                <i class="ion ion-ios7-person danger"></i> Você alterou seu username
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                                <li class="footer"><a href="#">Ver tudo</a></li>
                            </ul>
                        </li>
                        <!-- Tasks: style can be found in dropdown.less -->
                        <li class="dropdown tasks-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <i class="fa fa-tasks"></i>
                                <span class="label label-danger">9</span>
                            </a>
                            <ul class="dropdown-menu">
                                <li class="header">Você tem 9 tarefas</li>
                                <li>
                                    <!-- inner menu: contains the actual data -->
                                    <ul class="menu">
                                        <li><!-- Task item -->
                                            <a href="#">
                                                <h3>
                                                    Consultar por região
                                                    <small class="pull-right">20%</small>
                                                </h3>
                                                <div class="progress xs">
                                                    <div class="progress-bar progress-bar-aqua" style="width: 20%" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
                                                        <span class="sr-only">20% Completo</span>
                                                    </div>
                                                </div>
                                            </a>
                                        </li><!-- end task item -->
                                        <li><!-- Task item -->
                                            <a href="#">
                                                <h3>
                                                    Salvar consulta por campus
                                                    <small class="pull-right">40%</small>
                                                </h3>
                                                <div class="progress xs">
                                                    <div class="progress-bar progress-bar-green" style="width: 40%" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
                                                        <span class="sr-only">40% Completo</span>
                                                    </div>
                                                </div>
                                            </a>
                                        </li><!-- end task item -->
                                        <li><!-- Task item -->
                                            <a href="#">
                                                <h3>
                                                    Tarefas que preciso fazer
                                                    <small class="pull-right">60%</small>
                                                </h3>
                                                <div class="progress xs">
                                                    <div class="progress-bar progress-bar-red" style="width: 60%" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
                                                        <span class="sr-only">60% Completo</span>
                                                    </div>
                                                </div>
                                            </a>
                                        </li><!-- end task item -->
                                        <li><!-- Task item -->
                                            <a href="#">
                                                <h3>
                                                    Visualizar logs
                                                    <small class="pull-right">80%</small>
                                                </h3>
                                                <div class="progress xs">
                                                    <div class="progress-bar progress-bar-yellow" style="width: 80%" role="progressbar" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100">
                                                        <span class="sr-only">80% Completo</span>
                                                    </div>
                                                </div>
                                            </a>
                                        </li><!-- end task item -->
                                    </ul>
                                </li>
                                <li class="footer">
                                    <a href="#">Ver todas as tarefas</a>
                                </li>
                            </ul>
                        </li>
                        <!-- User Account: style can be found in dropdown.less -->
                        <li class="dropdown user user-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <i class="glyphicon glyphicon-user"></i>
                                <span>Admin <i class="caret"></i></span>
                            </a>
                            <ul class="dropdown-menu">
                                <!-- User image -->
                                <li class="user-header bg-light-blue">
                                    <img src="imagens/avatar.png" class="img-circle" alt="User Image" />
                                    <p>
                                        Admin - Data Scientist
                                        <small>Membro desde Dezembro</small>
                                    </p>
                                </li>
                                <!-- Menu Body -->
                                <li class="user-body">
                                    <div class="col-xs-4 text-center">
                                        <a href="#">menu 1</a>
                                    </div>
                                    <div class="col-xs-4 text-center">
                                        <a href="#">menu 2</a>
                                    </div>
                                    <div class="col-xs-4 text-center">
                                        <a href="#">menu 3</a>
                                    </div>
                                </li>
                                <!-- Menu Footer-->
                                <li class="user-footer">
                                    <div class="pull-left">
                                        <a href="#" class="btn btn-default btn-flat">Configurações</a>
                                    </div>
                                    <div class="pull-right">
                                        <a href="#" class="btn btn-default btn-flat">Sair</a>
                                    </div>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>
        <div class="wrapper row-offcanvas row-offcanvas-left">
            <!-- Left side column. contains the logo and sidebar -->
            <aside class="left-side sidebar-offcanvas">
                <!-- sidebar: style can be found in sidebar.less -->
                <section class="sidebar">
                    <!-- Sidebar user panel -->
                    <div class="user-panel">
                        <div class="pull-left image">
                            <img src="imagens/avatar.png" class="img-circle" alt="User Image" />
                        </div>
                        <div class="pull-left info">
                            <p>Olá, Admin</p>

                            <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
                        </div>
                    </div>
                    <!-- search form -->
                    <form action="#" method="get" class="sidebar-form">
                        <div class="input-group">
                            <input type="text" name="q" class="form-control" placeholder="Buscar..."/>
                            <span class="input-group-btn">
                                <button type='submit' name='search' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i></button>
                            </span>
                        </div>
                    </form>
                    <!-- /.search form -->
                    <!-- sidebar menu: : style can be found in sidebar.less -->
                    <ul class="sidebar-menu">
                        <li class="active">
                            <a href="index.php">
                                <i class="fa fa-dashboard"></i> <span>Dashboard</span>
                            </a>
                        </li>
                        
                        <li>
                            <a href="pages/calendar.html">
                                <i class="fa fa-calendar"></i> <span>Consultas Salvas</span>
                                <small class="badge pull-right bg-red">3</small>
                            </a>
                        </li>
                        <li>
                            <a href="pages/mailbox.html">
                                <i class="fa fa-envelope"></i> <span>Logs</span>
                                <small class="badge pull-right bg-yellow">12</small>
                            </a>
                        </li>
                        <li>
                            <a href="pages/widgets.html">
                                <i class="fa fa-th"></i> <span>FAQ</span> <small class="badge pull-right bg-green">new</small>
                            </a>
                        </li>
                        <li>
                            <a href="config.html">
                                <i class="fa fa-dashboard"></i> <span>Configurações</span>
                            </a>
                        </li>
                    </ul>
                </section>
                <!-- /.sidebar -->
            </aside>

            <!-- Right side column. Contains the navbar and content of the page -->
            <aside class="right-side">
                <!-- Content Header (Page header) -->
                <section class="content-header">
                    <h1>
                        Dashboard
                        <small>Painel de Controle</small>
                    </h1>
                    <ol class="breadcrumb">
                        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
                        <li class="active">Dashboard</li>
                    </ol>
                </section>

                <!-- Main content -->
                <section class="content">

                    <!-- Small boxes (Stat box) -->
                    <div class="row">
                        <div class="col-lg-3 col-xs-6">
                            <!-- small box -->
                            <div class="small-box bg-aqua">
                                <div class="inner">
                                    <h3>
                                        150
                                    </h3>
                                    <p>
                                        Ainda estou
                                    </p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-bag"></i>
                                </div>
                                <a href="#" class="small-box-footer">
                                    Mais... <i class="fa fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div><!-- ./col -->
                        <div class="col-lg-3 col-xs-6">
                            <!-- small box -->
                            <div class="small-box bg-green">
                                <div class="inner">
                                    <h3>
                                        53<sup style="font-size: 20px">%</sup>
                                    </h3>
                                    <p>
                                        decidindo
                                    </p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-stats-bars"></i>
                                </div>
                                <a href="#" class="small-box-footer">
                                    Mais... <i class="fa fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div><!-- ./col -->
                        <div class="col-lg-3 col-xs-6">
                            <!-- small box -->
                            <div class="small-box bg-yellow">
                                <div class="inner">
                                    <h3>
                                        44
                                    </h3>
                                    <p>
                                        o que
                                    </p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-person-add"></i>
                                </div>
                                <a href="#" class="small-box-footer">
                                    Mais... <i class="fa fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div><!-- ./col -->
                        <div class="col-lg-3 col-xs-6">
                            <!-- small box -->
                            <div class="small-box bg-red">
                                <div class="inner">
                                    <h3>
                                        65
                                    </h3>
                                    <p>
                                        colocar aqui
                                    </p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-pie-graph"></i>
                                </div>
                                <a href="#" class="small-box-footer">
                                    Mais... <i class="fa fa-arrow-circle-right"></i>
                                </a>
                            </div>
                        </div><!-- ./col -->
                    </div><!-- /.row -->

                    <!-- Main row -->
                    <div class="row">
                        <!-- Left col -->
                        <section class="col-lg-4 connectedSortable">         
                        <!-- Custom tabs (Charts with tabs)-->
                            <div class="nav-tabs-custom">
                                <!-- Tabs within a box -->
                                
                                <ul class="nav nav-tabs pull-right">
                                    <li>
                                        <a href="#revenue-chart">Gênero</a>
                                    </li>
                                    <li>
                                        <a href="#sales-chart">Campus</a>
                                    </li>
                                    <li>
                                        <a href="#crm-chart">Cr Médio</a>
                                    </li>
                                    <!-- <li>
                                        <a href="#populacao-chart">População</a>
                                    </li> -->
                                    <li class="pull-left header"><i class="fa fa-inbox"></i> Gráficos</li>
                                    
                                </ul>
                                <div class="tab-content no-padding">
                                    
                                    <div class="chart tab-pane active" id="revenue-chart" style="position: relative; height: 270px;">
                                        <div id="genero" width="250" height="250"></div>
                                    </div>

                                    <div class="chart tab-pane" id="sales-chart" style="position: relative; height: 270px;">
                                        <div id="campus" width="250" height="250"></div>
                                    </div>

                                    <div class="chart tab-pane" id="crm-chart" style="position: relative; height: 270px;">
                                        <div id="crm" width="250" height="250"></div>
                                    </div>

                                    <div class="chart tab-pane" id="populacao-chart" style="position: relative; height: 270px;">
                                        <div id="populacao" width="250" height="250"></div>
                                    </div>

                                </div>
                            </div><!-- /.nav-tabs-custom -->
                            <!-- Calendar -->
                            <div class="box box-solid bg-green-gradient">
                                <div class="box-header">
                                    <i class="fa fa-calendar"></i>
                                    <h3 class="box-title">Anotações</h3>
                                    <!-- tools box -->
                                    <div class="pull-right box-tools">
                                        <!-- button with a dropdown -->
                                        <div class="btn-group">
                                            <button class="btn btn-success btn-sm dropdown-toggle" data-toggle="dropdown"><i class="fa fa-bars"></i></button>
                                            <ul class="dropdown-menu pull-right" role="menu">
                                                <li><a href="#">Adicionar</a></li>
                                                <li><a href="#">Apagar</a></li>
                                                <li class="divider"></li>
                                                <li><a href="#">Ver Anotações</a></li>
                                            </ul>
                                        </div>
                                        <button class="btn btn-success btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
                                        <button class="btn btn-success btn-sm" data-widget="remove"><i class="fa fa-times"></i></button>                                        
                                    </div><!-- /. tools -->
                                </div><!-- /.box-header -->
                                <div class="box-body no-padding">
                                    
                                </div><!-- /.box-body -->  
                                <div class="box-footer text-black">
                                    <div class="row">
                                        
                                    </div><!-- /.row -->                                                                        
                                </div>
                            </div><!-- /.box -->  
                        </section><!-- /.Left col -->
                        <!-- right col (We are only adding the ID to make the widgets sortable)-->
                        <section class="col-lg-8 connectedSortable"> 
                            <!-- Map box -->
                            <div class="box box-solid bg-light-blue-gradient">
                                <div class="box-header">
                                    <!-- tools box -->
                                    <div class="pull-right box-tools">
                                        <button class="btn btn-primary btn-sm daterange pull-right" data-toggle="tooltip" title="Date range"><i class="fa fa-calendar"></i></button>
                                        <button class="btn btn-primary btn-sm pull-right" data-widget='collapse' data-toggle="tooltip" title="Collapse" style="margin-right: 5px;"><i class="fa fa-minus"></i></button>
                                    </div><!-- /. tools -->

                                    <i class="fa fa-map-marker"></i>
                                    <h3 class="box-title">
                                        Mapa
                                    </h3>
                                </div>
                                <div class="box-body">
                                    <div id="map"></div>
                                    <div class="aviosMap">
                                        <ul class="listaSemMarcador">
                                            <li> Use o botão com a <img src="imagens/hand.png" style="width:15px;height:15px;"> para poder navegar no mapa. </li>
                                            <li> Use o botão com o <img src="imagens/polygon.png" style="width:15px;height:15px;"> para conseguir desenhar um polígono no mapa.</li>
                                            <li> Use o <i> shift</i> ou um duplo clique do mouse, para poder fechar o polígono.</li>
                                        </ul>
                                    </div>
                                </div><!-- /.box-body-->
                                <div class="box-footer no-border">
                                    <!-- botões de busca -->
                                    <ul id="mapMenu" class="listaSemMarcador">
                                        <li class="liButtonMap"> 
                                            <input type="button" class="btn btn-primary mapMenuButton imagem" value="Buscar" 
                                                   id="enviar" data-toggle="modal" data-target="#myModal"> 
                                        </li>
                                        <li class="liButtonMap" >
                                            <input type="button" class="btn btn-primary mapMenuButton" value="Nova Busca" 
                                                   id="reset" onclick="novaBusca();">
                                        </li>
                                        <li class="liButtonMap" onclick="activePolygonDraw(0);">
                                            <div class="btn btn-primary mapMenuButton imagem">
                                                <input type="image" class="btn btn-primary imageButton" 
                                                   src="imagens/hand.png" alt="Hand"/>
                                            </div>
                                        </li>
                                        <li class="liButtonMap" onclick="activePolygonDraw(1);">
                                            <div class="btn btn-primary mapMenuButton imagem">
                                                <input type="image" class="btn btn-primary imageButton" 
                                                   src="imagens/polygon.png" alt="Polygon"/>
                                            </div>
                                        </li>
                                        <li class="liButtonMap"> 
                                            <input type="button" class="btn btn-primary mapMenuButton" value="Salvar Consulta" 
                                                   id="save" data-toggle="modal" data-target="#modalSave" onclick="salvarConsulta();"> 
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <!-- /.box -->
                        </section><!-- right col -->
                    </div><!-- /.row (main row) -->

                </section><!-- /.content -->
            </aside><!-- /.right-side -->
        </div><!-- ./wrapper -->
        <!-- guarda informaçoes dos pontos -->
        <input id="pontos">
        <input id="pdfs">
        <!-- envio de dados para o banco -->
        <form id="consultarPoligono" method="post" name="consultarPoligono"  action="">
            <input id="poligono" type="hidden" name="poligono">
            <input type="hidden" name="submitted" id="submitted" value="true" />
            <div id="filtros">
                <!-- MODAL -->
                <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                        <h4 class="modal-title">Escolha o filtro de busca</h4>
                      </div>
                      <div class="modal-body">
                        <p>Você deseja buscar por&hellip;</p>
                        <p>Sexo:
                            <input type="radio" name="sexo" value="F">Feminino
                            <input type="radio" name="sexo" value="M">Masculino
                            <input type="radio" name="sexo" value="">Ambos
                        </p>
                        <p>Situação:
                            <input type="radio" name="situacao" value="1">Ativo
                            <input type="radio" name="situacao" value="1">Inativo
                            <input type="radio" name="situacao" value="6">Formado
                        </p>
                        <p>Campus:
                            <input type="radio" name="campus" value="Seropédica">Seropédica
                            <input type="radio" name="campus" value="Nova Iguaçu">Nova Iguaçu
                            <input type="radio" name="campus" value="Três Rios">Três Rios
                            <input type="radio" name="campus" value="">Todos
                        </p>
                        <p class="texto">CR Acumulado acima de:
                            <input type="text" name="cra_aluno">
                        </p>
                        <p class="texto">Nome do Curso:
                            <input class="cod_curso" type="text" name="cod_curso">
                        </p>
                        <p class="texto">Naturalidade:
                            <input class="naturalidade" type="text" name="naturalidade">
                        </p>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                        <button type="button" class="btn btn-primary" data-dismiss="modal" onclick="enviaDados();">Buscar</button>
                      </div>
                    </div><!-- /.modal-content -->
                  </div><!-- /.modal-dialog -->
                </div><!-- /.modal -->
                </div>
        </form> 
        <!-- scripts -->
        <script type="text/javascript" src="scripts/jquery-2.0.3.js"></script>
        <script async type="text/javascript" src="scripts/jquery-ui.min.js"></script>
        <script src="http://maps.google.com/maps/api/js?v=3&amp;sensor=false" style=""></script>
        <script type="text/javascript" src="scripts/OpenLayers.js"></script>
        <script async type="text/javascript" src="scripts/bootstrap.min.js"></script>
        <script type="text/javascript" src="scripts/heatmap.js"></script>
        <script async type="text/javascript" src="scripts/heatmap-gmaps.js"></script>
        <script type="text/javascript" src="scripts/heatmap-openlayers.js"></script>
        <script async type="text/javascript" src="scripts/wsage.js"></script>
        <script type="text/javascript" src="scripts/smoothScroll.js"></script>
        <script type="text/javascript" src="scripts/Chart.js"></script>
        <script src="scripts/AdminLTE/app.js" type="text/javascript"></script>
        <script src="scripts/ol.js" type="text/javascript"></script>
    </body>
</html>
