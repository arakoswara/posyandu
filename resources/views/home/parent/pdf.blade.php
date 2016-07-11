<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title></title>
	<link rel="stylesheet" href="">
</head>
<body>

	<h4>No. Registrasi : {{ $data_balita->no_reg }} | Nama Balita : {{ $data_balita->nama_balita }} | Tanggal Lahir : {{ $data_balita->tgl_lahir }}</h4>

	<hr>

	<table>
		<tbody>

				<tr>
					<td><h4>Tanggal periksa</h4></td>
					<td>:</td>
					<td><h4>{{ $data->periksa->tgl_periksa }}</h4></td>
				</tr>

				<tr>
					<td>Berat badan</td>
					<td>:</td>
					<td>{{ $data->periksa->berat_badan }}</td>
				</tr>

				<tr>
					<td>Tinggi badan</td>
					<td>:</td>
					<td>{{ $data->periksa->tinggi_badan }}</td>
				</tr>

				<tr>
					<td>BB / U</td>
					<td>:</td>
					<td>{{ $data->zbbu }}</td>
				</tr>

				<tr>
					<td>TB / U</td>
					<td>:</td>
					<td>{{ $data->ztbu }}</td>
				</tr>

				<tr>
					<td>BB / TB</td>
					<td>:</td>
					<td>{{ $data->zbbtb }}</td>
				</tr>

				<tr>
					<td>Kebutuhan protein</td>
					<td>:</td>
					<td>{{ $data->protein }}</td>
				</tr>

				<tr>
					<td>Kebutuhan energi</td>
					<td>:</td>
					<td>{{ $data->energi }}</td>
				</tr>

				<tr>
					<td colspan="3">
						<hr>
					</td>
				</tr>

		</tbody>
	</table>
	
</body>
</html>