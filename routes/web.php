<?php

use App\Http\Controllers\Admin\AcompanhaOrdemController;
use App\Http\Controllers\Admin\LotePcpController;
use App\Http\Controllers\Admin\PneusLotePcpController;
use App\Http\Controllers\Admin\ProducaoEtapaController;
use App\Http\Controllers\admin\ProdutividadeController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;

/* Rotas de Login */
Route::get('/', [LoginController::class, 'showLoginForm'])->name('login');
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('admin.login');
Route::post('/login/do', [LoginController::class, 'Login'])->name('admin.login.do');
Route::get('/logout', [LoginController::class, 'logout'])->name('admin.logout');
Route::get('/admin', [LoginController::class, 'dashboard'])->name('admin.dashborad');

Route::middleware(['auth'])->group(function () {
 Route::prefix('producao')->group(function () {
  /* Routas Etapas */
  Route::get('etapas', [ProducaoEtapaController::class, 'index'])->name('admin.producao.etapas');
  Route::post('etapas', [ProducaoEtapaController::class, 'index'])->name('admin.producao.etapas');
  /*Rotas de acompanhamento de ordem */
  Route::get('acompanha-ordem', [AcompanhaOrdemController::class, 'index'])->name('admin.producao.acompanha.ordem');
  Route::get('acompanha-ordem/{codigo_barras}', [AcompanhaOrdemController::class, 'index'])->name('admin.producao.acompanha.ordem');
  Route::post('acompanha-ordem', [AcompanhaOrdemController::class, 'statusOrdem'])->name('admin.producao.acompanha.ordem');
  /*Rotas Quantide lote e atrasos*/
  Route::get('lote-pcp', [LotePcpController::class, 'index'])->name('admin.lote.pcp');
  Route::get('lote-pcp/{nr_lote}/pneus-lote', [PneusLotePcpController::class, 'index'])->name('admin.lote.pneu.pcp');
 });
 Route::prefix('usuarios')->group(function () {
  Route::get('/', [UserController::class, 'index'])->name('admin.usuarios.listar');
  Route::get('listar', [UserController::class, 'index'])->name('admin.usuarios.listar');
  Route::get('editar/{id}', [UserController::class, 'edit'])->name('admin.usuarios.edit');
  Route::get('delete/{id}', [UserController::class, 'delete'])->name('admin.usuarios.delete');
  Route::post('cadastrar', [UserController::class, 'create'])->name('admin.usuarios.create');
  Route::post('atualizar', [UserController::class, 'update'])->name('admin.usuarios.update');
 });

});

Route::prefix('producao')->group(function () {
 /*Rotas de acompanhamento de ordem */
 Route::get('acompanha-ordem', [AcompanhaOrdemController::class, 'index'])->name('admin.producao.acompanha.ordem');
 Route::get('acompanha-ordem/{codigo_barras}', [AcompanhaOrdemController::class, 'index'])->name('admin.producao.acompanha.ordem');
 Route::post('acompanha-ordem', [AcompanhaOrdemController::class, 'statusOrdem'])->name('admin.producao.acompanha.ordem');

 /*Produtividade Executores*/
 Route::get('produtividade-executores/quadrante-1', [ProdutividadeController::class, 'index'])->name('admin.producao.quadrante1');
 Route::get('produtividade-executores/quadrante-2', [ProdutividadeController::class, 'index'])->name('admin.producao.quadrante2');
 Route::get('produtividade-executores/quadrante-3', [ProdutividadeController::class, 'index'])->name('admin.producao.quadrante3');
 Route::get('produtividade-executores/quadrante-4', [ProdutividadeController::class, 'index'])->name('admin.producao.quadrante4');
});
