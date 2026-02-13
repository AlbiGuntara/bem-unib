<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Tanggal</th>
            <th>Uraian</th>
            <th>Jenis</th>
            <th>Nominal</th>
            <th>Kategori</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $i => $row)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $row->tanggal }}</td>
                <td>{{ $row->uraian }}</td>
                <td>{{ $row->jenis }}</td>
                <td>{{ $row->nominal }}</td>
                <td>{{ $row->kategori?->kategori }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
