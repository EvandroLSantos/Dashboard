<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoteEntradaEstoque extends Model
{
    use HasFactory;
    protected $fillable = [
        'descricao',
        'cd_usuario',
        'status'
    ];
    
    public $timestamps = true;
    public $table = 'lote_entrada_estoques';
    protected $connection;

    public function lotesAll()
    {
        return LoteEntradaEstoque::all();
    }

    public function storeData($input)
    {
        LoteEntradaEstoque::create([
            'descricao' => $input['ds_lote'],
            'cd_usuario' => $input['cd_usuario'],
            'status' => $input['status']
        ]);
    }
    public function findLote($id)
    {
        return LoteEntradaEstoque::findOrFail($id);
    }

    public function updateData($data, $qtd_item)
    {
        LoteEntradaEstoque::where('id', $data->id)->update(['status' => 'F', 'ps_liquido_total' => $data->peso, 'qtd_itens' => $qtd_item]);
        return response()->json(['success' => 'Lote finalizado com sucesso!']);
    }
    public function deleteData($id)
    {
        try {
            LoteEntradaEstoque::find($id)->delete();
        } catch (\Throwable $th) {
            return response()->json(['error' => "Esse lote não pode ser excluido a item nele!"]);
        }
        return response()->json(['success' => 'Lote excluido com sucesso!']);
    }
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('d/m/Y H:i:s');
    }
}
