<?php

use App\Http\Controllers\Admin\AcompanhaOrdemController;
use App\Http\Controllers\Admin\Comercial\ComercialController;
use App\Http\Controllers\Admin\Email\EmailController;
use App\Http\Controllers\Admin\EmpresaController;
use App\Http\Controllers\Admin\LotePcpController;
use App\Http\Controllers\Admin\MarcaModeloFrotaController;
use App\Http\Controllers\Admin\MarcaVeiculoController;
use App\Http\Controllers\Admin\ModeloVeiculoController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\Pessoa\PessoaController;
use App\Http\Controllers\Admin\PneusLotePcpController;
use App\Http\Controllers\Admin\PortariaController;
use App\Http\Controllers\Admin\ProducaoEtapaController;
use App\Http\Controllers\admin\ProdutividadeController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\VeiculoController;
use App\Http\Controllers\Auth\LoginController;
use App\Models\MarcaModeloFrota;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Route;

Route::get('/clear-cache-all', function () {

    Artisan::call('cache:clear');

    dd("Cache Clear All");
});

/* Rotas de Login */
Route::get('/', [LoginController::class, 'showLoginForm'])->name('login');
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('admin.login');
Route::post('/login/do', [LoginController::class, 'Login'])->name('admin.login.do');
Route::get('/logout', [LoginController::class, 'logout'])->name('admin.logout');
Route::get('/admin', [LoginController::class, 'dashboard'])->name('admin.dashborad');

Route::get('/empresa', [EmpresaController::class, 'index'])->name('empresa.index');

Route::middleware(['auth', 'role:admin|producao'])->group(function () {
    Route::prefix('producao')->group(function () {
        /* Routas Etapas */
        Route::get('etapas', [ProducaoEtapaController::class, 'index'])->name('admin.producao.etapas');
        Route::post('etapas', [ProducaoEtapaController::class, 'index'])->name('admin.producao.etapas.do');

        /*Rotas Quantide lote e atrasos*/
        Route::get('lote-pcp', [LotePcpController::class, 'index'])->name('admin.lote.pcp');
        Route::get('lote-pcp/{nr_lote}/pneus-lote', [PneusLotePcpController::class, 'index'])->name('admin.lote.pneu.pcp');
    });

    Route::middleware(['auth', 'role:admin'])->prefix('usuarios')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('admin.usuarios');
        Route::get('listar', [UserController::class, 'index'])->name('admin.usuarios.listar');
        Route::get('editar/{id}', [UserController::class, 'edit'])->name('admin.usuarios.edit');
        Route::get('delete/{id}', [UserController::class, 'delete'])->name('admin.usuarios.delete');
        Route::get('cadastrar', [UserController::class, 'index'])->name('admin.usuarios.create');
        Route::post('cadastrar', [UserController::class, 'create'])->name('admin.usuarios.create.do');
        Route::post('atualizar', [UserController::class, 'update'])->name('admin.usuarios.update');

        /*Rotas funções*/
        Route::get('funcao', [RoleController::class, 'index'])->name('admin.usuarios.role');
        Route::get('funcao/editar/{id}', [RoleController::class, 'edit'])->name('admin.usuarios.role.edit');
        Route::post('funcao/editar', [RoleController::class, 'update'])->name('admin.usuarios.role.edit.do');
        Route::get('funcao/novo', [RoleController::class, 'create'])->name('admin.usuarios.role.create');
        Route::post('funcao/novo', [RoleController::class, 'save'])->name('admin.usuarios.role.create.do');
        Route::get('funcao/delete/{id}', [RoleController::class, 'delete'])->name('admin.usuarios.role.delete');

        /*Rotas permission*/
        Route::get('permissao', [PermissionController::class, 'index'])->name('admin.usuarios.permission');
        Route::get('permissao/editar/{id}', [PermissionController::class, 'edit'])->name('admin.usuarios.permission.edit');
        Route::post('permissao/editar', [PermissionController::class, 'update'])->name('admin.usuarios.permission.edit.do');
        Route::get('permissao/novo', [PermissionController::class, 'create'])->name('admin.usuarios.permission.create');
        Route::post('permissão/novo', [PermissionController::class, 'save'])->name('admin.usuarios.permission.create.do');
        Route::get('permissao/delete/{id}', [PermissionController::class, 'delete'])->name('admin.usuarios.permission.delete');
    });
});

/*Rotas sem autenticação*/
Route::prefix('producao')->group(function () {
    /*Rotas de acompanhamento de ordem */
    Route::get('acompanha-ordem', [AcompanhaOrdemController::class, 'index'])->name('admin.producao.acompanha.ordem');
    Route::get('acompanha-ordem/{codigo_barras}', [AcompanhaOrdemController::class, 'index'])->name('admin.producao.acompanha.ordem.barras');
    Route::post('acompanha-ordem', [AcompanhaOrdemController::class, 'statusOrdem'])->name('admin.producao.acompanha.ordem.do');

    /*Produtividade Executores*/
    Route::get('produtividade-executores/quadrante-1', [ProdutividadeController::class, 'index'])->name('admin.producao.quadrante1');
    Route::get('produtividade-executores/quadrante-2', [ProdutividadeController::class, 'index'])->name('admin.producao.quadrante2');
    Route::get('produtividade-executores/quadrante-3', [ProdutividadeController::class, 'index'])->name('admin.producao.quadrante3');
    Route::get('produtividade-executores/quadrante-4', [ProdutividadeController::class, 'index'])->name('admin.producao.quadrante4');
});

Route::middleware(['auth', 'role:admin|portaria'])->group(function () {
    Route::prefix('portaria')->group(function () {
        Route::get('movimento/entrada', [PortariaController::class, 'index'])->name('admin.portaria.entrada');        
        Route::get('movimento/saida', [PortariaController::class, 'index'])->name('admin.portaria.saida');

        Route::post('movimento/entrada', [PortariaController::class, 'list'])->name('portaria.entrada');
        Route::post('movimento/saida', [PortariaController::class, 'list'])->name('portaria.saida');

        Route::post('movimento/entrada/do', [PortariaController::class, 'saveEntrada'])->name('portaria.entrada.do');
        Route::post('movimento/saida/do', [PortariaController::class, 'saveSaida'])->name('portaria.saida.do');

    });
    Route::prefix('veiculo')->group(function () {
        Route::get('listar', [VeiculoController::class, 'index'])->name('listar.motorista.veiculos');
        Route::get('get-motorista-veiculos', [VeiculoController::class, 'getMotoristaVeiculos'])->name('buscar.motorista.veiculo');
        Route::post('store', [VeiculoController::class, 'store'])->name('motorista-veiculo.store');
        Route::get('edit/{id}', [VeiculoController::class, 'edit'])->name('motorista-veiculo.edit');
        Route::post('edit/{id}/do', [VeiculoController::class, 'update'])->name('motorista-veiculo.update');
        Route::delete('delete/{id}', [VeiculoController::class, 'destroy'])->name('motorista-veiculo.destroy');
    });
    /*Rotas Marca Veiculo */
    Route::prefix('marca/veiculo')->group(function () {
        Route::get('listar', [MarcaVeiculoController::class, 'create'])->name('marca-veiculo');
        Route::post('cadastrar/do', [MarcaVeiculoController::class, 'save'])->name('marca-veiculo.salvar');
        Route::post('delete', [MarcaVeiculoController::class, 'delete'])->name('marca-veiculo.delete');
        Route::post('editar', [MarcaVeiculoController::class, 'update'])->name('marca-veiculo.update');
    });
    /*Rotas Modelos Veiculos */
    Route::prefix('modelo/veiculo')->group(function () {
        Route::get('listar', [ModeloVeiculoController::class, 'create'])->name('modelo-veiculo');
        Route::post('cadastrar/do', [ModeloVeiculoController::class, 'save'])->name('modelo-veiculo.salvar');
        Route::post('delete', [ModeloVeiculoController::class, 'delete'])->name('modelo-veiculo.delete');
        Route::post('editar', [ModeloVeiculoController::class, 'update'])->name('modelo-veiculo.update');
    });
    /*Rotas Marca-Modelos Veiculos */
    Route::prefix('marca-modelo-frota/veiculo')->group(function () {
        Route::get('listar', [MarcaModeloFrotaController::class, 'index'])->name('marca-modelo.index');
        Route::get('get-marca-modelos', [MarcaModeloFrotaController::class, 'getMarcaModelos'])->name('get-marca-modelos');
        Route::post('store', [MarcaModeloFrotaController::class, 'store'])->name('marca-modelo.store');
        Route::get('edit/{id}', [MarcaModeloFrotaController::class, 'edit'])->name('marca-modelo.update');
        Route::post('edit/{id}/do', [MarcaModeloFrotaController::class, 'update'])->name('marca-modelo.update.do');
        Route::delete('delete/{id}', [MarcaModeloFrotaController::class, 'destroy'])->name('marca-modelo.delete');
    });
    //Rotas Pessoa
    Route::prefix('pessoa')->group(function () {
        Route::get('listar', [PessoaController::class, 'index'])->name('pessoa.index');
        Route::get('get-pessoa', [PessoaController::class, 'getpessoa'])->name('get-pessoa');
        Route::post('store', [PessoaController::class, 'store'])->name('pessoa.store');
        Route::get('edit/{id}', [PessoaController::class, 'edit'])->name('pessoa.update');
        Route::post('edit/{id}/do', [PessoaController::class, 'update'])->name('pessoa.update.do');
        Route::delete('delete/{id}', [PessoaController::class, 'destroy'])->name('pessoa.delete');
    });
    //Rotas Email
    Route::prefix('email')->group(function () {
        Route::get('listar', [EmailController::class, 'index'])->name('email.index');
        Route::get('get-email', [EmailController::class, 'getemail'])->name('get-email');
        Route::post('store', [EmailController::class, 'store'])->name('email.store');
        Route::get('edit/{id}', [EmailController::class, 'edit'])->name('email.update');
        Route::post('edit/{id}/do', [EmailController::class, 'update'])->name('email.update.do');
        Route::delete('delete/{id}', [EmailController::class, 'destroy'])->name('email.delete');
    });

    Route::prefix('placa')->group(function () {
        Route::post('autocomplete', [PortariaController::class, 'autocomplete'])->name('placa.autocomplete');
        Route::get('search', [PortariaController::class, 'search'])->name('placa.search');
    });
});

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::prefix('comercial')->group(function () {
        Route::get('listar', [ComercialController::class, 'index'])->name('comercial.index');
    });
});
