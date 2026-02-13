<?php

namespace App\Exports;

use App\Models\Admin\KasKegiatan;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class KasKegiatanExport implements FromView
{
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function view(): View
    {
        $query = KasKegiatan::with('kategori');

        if ($this->request->search) {
            $query->where('uraian', 'like', '%'.$this->request->search.'%');
        }

        if (
            $this->request->sort &&
            in_array($this->request->direction, ['asc', 'desc'])
        ) {
            $query->orderBy(
                $this->request->sort,
                $this->request->direction
            );
        }

        return view('exports.kas', [
            'data' => $query->get(),
        ]);
    }
}
