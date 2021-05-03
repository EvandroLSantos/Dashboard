<?php

namespace App\Http\Controllers\admin;

use App\Charts\ProdutividadeExecutadoresChart;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class ProdutividadeController extends Controller
{
 public function index()
 {

  $sql = "
        SELECT substring(X.NMEXECUTOR from 1 for position(' ', X.NMEXECUTOR)-1) NMEXECUTOR, SUM(X.HOJE) HOJE, SUM(X.ONTEM) ONTEM, SUM(X.ANTEONTEM) ANTEONTEM
        FROM (
           SELECT E.NMEXECUTOR, COUNT(I.ID) HOJE, 0 ONTEM, 0 ANTEONTEM
           FROM ESCAREACAOPNEU I
           INNER JOIN EXECUTORETAPA E ON (I.IDEXECUTOR = E.ID)
           INNER JOIN ORDEMPRODUCAORECAP OPR ON (OPR.ID = I.IDORDEMPRODUCAORECAP)
           INNER JOIN ITEMPEDIDOPNEU IPP ON (IPP.ID = OPR.IDITEMPEDIDOPNEU)
           INNER JOIN PEDIDOPNEU PP ON (PP.ID = IPP.IDPEDIDOPNEU)
           WHERE CAST(I.DTFIM AS DATE) = CURRENT_DATE-4
            AND I.ST_ETAPA = 'F'
            AND PP.IDEMPRESA IN (1,2,3)
           GROUP BY  E.NMEXECUTOR
           HAVING COUNT(I.ID) > 5

        UNION ALL

           SELECT E.NMEXECUTOR, 0 HOJE, COUNT(I.ID) ONTEM, 0 ANTEONTEM
           FROM ESCAREACAOPNEU I
           INNER JOIN EXECUTORETAPA E ON (I.IDEXECUTOR = E.ID)
           INNER JOIN ORDEMPRODUCAORECAP OPR ON (OPR.ID = I.IDORDEMPRODUCAORECAP)
           INNER JOIN ITEMPEDIDOPNEU IPP ON (IPP.ID = OPR.IDITEMPEDIDOPNEU)
           INNER JOIN PEDIDOPNEU PP ON (PP.ID = IPP.IDPEDIDOPNEU)
          WHERE CAST(I.DTFIM AS DATE) = CURRENT_DATE-5
            AND I.ST_ETAPA = 'F'
            AND PP.IDEMPRESA IN (1,2,3)
           GROUP BY  E.NMEXECUTOR
           HAVING COUNT(I.ID) > 5

        UNION ALL

           SELECT E.NMEXECUTOR, 0 HOJE, 0 ONTEM, COUNT(I.ID) ANTEONTEM
           FROM ESCAREACAOPNEU I
           INNER JOIN EXECUTORETAPA E ON (I.IDEXECUTOR = E.ID)
           INNER JOIN ORDEMPRODUCAORECAP OPR ON (OPR.ID = I.IDORDEMPRODUCAORECAP)
           INNER JOIN ITEMPEDIDOPNEU IPP ON (IPP.ID = OPR.IDITEMPEDIDOPNEU)
           INNER JOIN PEDIDOPNEU PP ON (PP.ID = IPP.IDPEDIDOPNEU)
           WHERE CAST(I.DTFIM AS DATE) = CURRENT_DATE-6
            AND I.ST_ETAPA = 'F'
            AND PP.IDEMPRESA IN (1,2,3)
           GROUP BY  E.NMEXECUTOR
           HAVING COUNT(I.ID) > 5
        ) X
        GROUP BY X.NMEXECUTOR
        ORDER BY HOJE DESC
        ";

  $result_escareacao = DB::connection('firebird')->select($sql);

  foreach ($result_escareacao as $key => $escareacao) {
   $keys_escareacao[] = $escareacao->NMEXECUTOR;
   $value_hoje[]      = $escareacao->HOJE;
   $value_ontem[]     = $escareacao->ONTEM;
   $value_anteontem[] = $escareacao->ANTEONTEM;
   $mediaDia[]= 100;
  }
  //dd($mediaDia);
  $chart = $this->GeraCharts($keys_escareacao, $value_hoje, $value_ontem, $value_anteontem, $mediaDia);
  return view('admin.producao.produtividade-executores', compact('chart'));
 }

 public function GeraCharts($keys, $hoje, $ontem, $anteontem, $media)
 {

  $chart = new ProdutividadeExecutadoresChart;

  $chart->labels($keys);
  $chart->dataset('Hoje', 'bar', $hoje)
   ->options([
    'backgroundColor' => '#3FBF3F',
   ]);
  $chart->dataset('Ontem', 'bar', $ontem)
  ->options([
    'backgroundColor' => '#3F3FBF',
   ]);
  $chart->dataset('Anteontem', 'bar', $anteontem)
  ->options([
    'backgroundColor' => '#C43535',
   ]);
   $chart->dataset('Media Dia', 'line', $media)
  ->options([
    'color' => '#F0E118',
   ]);
  return $chart;
 }
}
