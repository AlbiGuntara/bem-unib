<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Data Warga</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            margin: 20px;
            color: #111827;
            /* text-gray-900 */
        }

        h2 {
            text-align: center;
            color: #16a34a;
            /* green-600 */
            margin-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            table-layout: fixed;
            word-wrap: break-word;
        }

        th,
        td {
            border: 1px solid #d1d5db;
            /* gray-300 */
            padding: 6px 8px;
            text-align: left;
            vertical-align: top;
        }

        th {
            background-color: #16a34a;
            /* green-600 */
            color: white;
            text-transform: uppercase;
            font-size: 10px;
            letter-spacing: 0.5px;
        }

        th.center {
            text-align: center;
            vertical-align: middle;
        }

        tr:nth-child(even) {
            background-color: #f3f4f6;
            /* gray-100 */
        }

        .center {
            text-align: center;
        }

        .small {
            font-size: 10px;
        }
    </style>
</head>

<body>
    <h2>Data Warga</h2>
    <table>
        <thead>
            <tr>
                <th rowspan="2" class="center">No</th>
                <th rowspan="2" class="center">Nama</th>
                <th rowspan="2" class="center">NIS</th>
                <th rowspan="2" class="center">Asrama</th>
                <th colspan="3" class="center">Pendidikan</th>
                <th rowspan="2" class="center">Wali</th>
                <th rowspan="2" class="center">No. Telp Wali</th>
                <th rowspan="2" class="center">Alamat</th>
                <th rowspan="2" class="center">Status</th>
                <th rowspan="2" class="center">Jabatan</th>
                <th rowspan="2" class="center">Tahun Mondok</th>
                <th rowspan="2" class="center">Tahun Lulus</th>
            </tr>
            <tr>
                <th class="center">Pagi</th>
                <th class="center">Siang</th>
                <th class="center">Malam</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($wargas as $index => $w)
                <tr>
                    <td class="center">{{ $index + 1 }}</td>
                    <td>{{ $w->nama }}</td>
                    <td>{{ $w->nis }}</td>
                    <td>{{ $w->asrama }}</td>
                    <td>{{ $w->pendidikan_pagi }} / {{ $w->kelas_pendidikan_pagi }}</td>
                    <td>{{ $w->pendidikan_sore }} / {{ $w->kelas_pendidikan_sore }}</td>
                    <td>{{ $w->pendidikan_malam }} / {{ $w->kelas_pendidikan_malam }}</td>
                    <td>{{ $w->wali }}</td>
                    <td>{{ $w->no_telp_wali }}</td>
                    <td>{{ $w->kecamatan }} - {{ $w->kabupaten }}</td>
                    <td>{{ $w->status }}</td>
                    <td>{{ $w->jabatan }}</td>
                    <td>{{ $w->tahun_mondok }}</td>
                    <td>{{ $w->tahun_lulus }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
