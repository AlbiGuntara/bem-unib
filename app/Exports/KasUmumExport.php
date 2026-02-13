<?php

namespace App\Exports;

use App\Models\Admin\KasUmum;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class KasUmumExport implements FromView
{
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function view(): View
    {
        $query = KasUmum::with('kategori');

        if ($this->request->search) {
            $query->where('uraian', 'like', '%'.$this->request->search.'%');
        }

        if ($this->request->jenis) {
            $query->where('jenis', $this->request->jenis);
        }

        if ($this->request->sort) {
            $query->orderBy(
                $this->request->sort,
                $this->request->direction ?? 'asc'
            );
        }

        return view('exports.kas-umum', [
            'data' => $query->get(),
        ]);
    }
}
