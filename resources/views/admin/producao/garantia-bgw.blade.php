@extends('admin.master.master')

@section('content')
    <!-- Main content -->
    <section class="content">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <div class="col-md-12">
                <div class="box box-info collapsed-box">
                    <div class="box-header with-border">
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i
                                    class="fa fa-plus"></i>
                            </button>
                        </div>
                        <h4 class="box-title">Buscar Pneus para Envio</h4>
                    </div>
                    <div class="box-body">
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="fa fa-building"></i></span>
                                    <select class="form-control select2" id="empresas" style="width: 100%;">
                                        <option selected="selected" value="0">Selecione uma empresa</option>
                                        @foreach ($empresas as $e)
                                            <option value="{{ $e->cd_empresa }}">{{ $e->ds_local }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="input-group">
                                    <div class="input-group-addon">
                                        <i class="fa fa-clock-o"></i>
                                    </div>
                                    <input type="text" class="form-control pull-right" id="daterange" value=""
                                        autocomplete="off">
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-success mb-2" id="btn-search">Buscar</button>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                @includeIf('admin.master.messages')
                <div class="nav-tabs-custom" style="cursor: move;">
                    <ul class="nav nav-tabs pull-right ui-sortable-handle">
                        <li class="pull-left active"><a href="#pneus-bgw" data-toggle="tab" aria-expanded="true">Pneus</a>
                        </li>
                        <li class="pull-left"><a href="#logs-bgw" data-toggle="tab" aria-expanded="false">Logs</a>
                        </li>
                        <li class="header"><i class="fa fa-inbox"></i> Pneus Ouro - BGW</li>
                    </ul>
                    <div class="tab-content no-padding">
                        <div class="tab-pane active" id="pneus-bgw">
                            <table class="table table-striped table-bordered compact" id="table-bgw" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Ordem</th>
                                        <th>Cliente</th>
                                        <th>Medida</th>
                                        <th>Serie</th>
                                        <th>Fogo</th>
                                        <th>Dot</th>
                                        <th>Desenho</th>
                                        <th>Marca</th>
                                        <th>Ciclo</th>
                                        <th>Preço</th>
                                        <th>Exportado</th>
                                    </tr>
                                </thead>
                                <tbody class="">
                                    @foreach ($pneus as $p)
                                        <tr>
                                            <td>{{ $p->ORD_NUMERO }}</td>
                                            <td>{{ $p->CLI_NOME }}</td>
                                            <td>{{ $p->MEDIDA }}</td>
                                            <td>{{ $p->MATRICULA }}</td>
                                            <td>{{ $p->FOGO }}</td>
                                            <td>{{ $p->DOT }}</td>
                                            <td>{{ $p->DESENHOPNEU }}</td>
                                            <td>{{ $p->MARCA }}</td>
                                            <td>{{ $p->COD_I_CICLO }}</td>
                                            <td>{{ $p->PRECO }}</td>
                                            <td>{{ $p->EXPORTADO }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="mx-auto">
                                <button class="btn btn-block btn-success" id="pross-pneus">Processar Pneus</button>
                            </div>
                        </div>
                        <div class="tab-pane log-bgw" id="logs-bgw">
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.row -->
    </section>
    <!-- /.content -->
@endsection
@section('scripts')
    @includeIf('admin.master.datatables')
    <script type="text/javascript">
        $(document).ready(function() {
            var inicioData = 0;
            var fimData = 0;
            $('#daterange').on('apply.daterangepicker', function(ev, picker) {
                $(this).val(picker.startDate.format('DD/MM/YYYY HH:mm') + ' - ' + picker.endDate.format(
                    'DD/MM/YYYY HH:mm'));
                inicioData = picker.startDate.format('MM/DD/YYYY HH:mm');
                fimData = picker.endDate.format('MM/DD/YYYY HH:mm');
            });
            $('#daterange').on('cancel.daterangepicker', function(ev, picker) {
                $(this).val("");
                inicioData = 0;
                fimData = 0;
            });
            $('#daterange').daterangepicker({
                //opens: 'left',
                autoUpdateInput: false,
                // timePicker: true,
                //timePickerIncrement: 30,
                // locale: {
                //     format: 'MM/DD/YYYY HH:mm',

                // }
            });
            $('#btn-search').click(function() {
                var empresa = $('#empresas').val();
                if (inicioData == 0 || empresa == '') {
                    alert('Empresa e Periodo deve ser informado!')
                    return false;
                }
                $.ajax({
                    url: "{{ route('api-new-age-import-pneus') }}",
                    method: "GET",
                    data: {
                        empresa: empresa,
                        inicio_data: inicioData,
                        fim_data: fimData,
                    },
                    beforeSend: function() {
                        // $("#table-search").DataTable().destroy();
                        $("#loading").removeClass('hidden');
                    },
                    success: function(result) {
                        $("#loading").addClass('hidden');
                        location.reload(true);
                    }
                });


            })
            $('#empresas').select2();
            $('#table-bgw').DataTable({});
            $('#pross-pneus').click(function() {
                $.ajax({
                    url: '{{ route('NewAgecallXmlProcess') }}',
                    method: "GET",
                    beforeSend: function() {
                        $("#loading").removeClass('hidden');
                        $("#pross-pneus").text('Processando...')
                    },
                    success: function(result) {
                        $("#pross-pneus").text('Processar Pneus')
                        $("#loading").addClass('hidden');
                        // console.log(result);
                        $("#table-log").remove();
                        $(".log-bgw").append(result);
                        $('.nav-tabs a[href="#logs-bgw"]').tab('show')
                        $('#table-log1').DataTable({
                            scrollY: "300px",
                            scrollX: true,
                            scrollCollapse: true,
                            paging: false,
                            columnDefs: [{
                                width: '20%',
                                targets: 2
                            }],
                            fixedColumns: true
                        });
                    }

                });
            });            
        });
    </script>
@endsection