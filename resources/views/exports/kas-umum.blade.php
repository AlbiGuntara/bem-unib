<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Tanggal</th>
            <th>Uraian</th>
            <th>Jenis</th>
            <th>Kategori</th>
            <th>Nominal</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $i => $item)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $item->tanggal }}</td>
                <td>{{ $item->uraian }}</td>
                <td>{{ $item->jenis }}</td>
                <td>{{ $item->kategori->kategori ?? '-' }}</td>
                <td>{{ $item->nominal }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
