<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">

        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu" data-widget="tree">
            <li class="header">MENU DE NAVEGAÇÃO</li>
            <li class="treeview" style="height: auto;">
                <a href="#">
                    <i class="fa fa-dashboard"></i> <span>Dashboard</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu" style="display: none;">
                    <li class="active"><a href="{{route('admin.dashborad')}}"><i class="fa fa-home"></i>Inicio</a>
                    </li>
                    <li class="active"><a href=""><i class="fa fa-circle-o"></i>Status Expedição</a>
                    </li>
                </ul>
            </li>
            <li class="treeview" style="height: auto;">
                <a href="#">
                    <i class="fa fa-th"></i> <span>Produção</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu" style="display: none;">
                    <li>
                        <a href="{{route('admin.producao.etapas')}}"><i class="fa fa-circle-o"></i>Produção por
                            Etapa</a>
                    </li>
                    <li>
                        <a href="{{route('admin.lote.pcp')}}"><i class="fa fa-circle-o"></i>Lote PCP</a>
                    </li>
                    <li>
                        <a href="{{route('admin.producao.acompanha.ordem')}}"><i class="fa fa-circle-o"></i>Acompanhar
                            Ordem</a>
                    </li>
                    <li>
                    <li class="treeview">
                        <a href="#"><i class="fa fa-circle-o"></i> Produtividade
                            <span class="pull-right-container">
                                <i class="fa fa-angle-left pull-right"></i>
                            </span>
                        </a>
                        <ul class="treeview-menu">                            
                            <li><a href="{{route('admin.producao.quadrante1')}}"><i class="fa fa-circle-o"></i>Quadrante 1</a></li>
                            <li><a href="{{route('admin.producao.quadrante2')}}"><i class="fa fa-circle-o"></i>Quadrante 2</a></li>
                            <li><a href="{{route('admin.producao.quadrante3')}}"><i class="fa fa-circle-o"></i>Quadrante 3</a></li>
                            <li><a href="{{route('admin.producao.quadrante4')}}"><i class="fa fa-circle-o"></i>Quadrante 4</a></li>
                        </ul>
                    </li>
                </ul>
            </li>
        </ul>
    </section>
    <!-- /.sidebar -->
</aside>