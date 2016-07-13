<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title></title>
	<link rel="stylesheet" href="">
</head>
<body>

	<h4>No. Registrasi : {{ $data['no_reg'] }}  | Nama Balita : {{ $data['nama_balita'] }} | Tanggal Lahir : {{ $data['tgl_lahir'] }}</h4>

	<hr>

	@if (!empty($data)) 

		<table>
			<tbody>

					<tr>
						<td><h4>Tanggal periksa</h4></td>
						<td>:</td>
						<td><h4>{{ $data['tgl_periksa'] }}</h4></td>
					</tr>

					<tr>
						<td>Berat badan</td>
						<td>:</td>
						<td>{{ $data['berat_badan'] }} Kg</td>
					</tr>

					<tr>
						<td>Tinggi badan</td>
						<td>:</td>
						<td>{{ $data['tinggi_badan'] }} cm</td>
					</tr>

					<tr>
					    <td>BB / U</td>
					    <td> : </td>
					    <td>{{ $data['zbbu'] }} |

					        @if($data['zbbu'] < -3)

					            {{ "Gizi Buruk" }}

					        @elseif($data['zbbu'] >= -3 && $data['zbbu'] < -2)

					            {{ "Gizi Kurang" }}

					        @elseif($data['zbbu'] >= -2 && $data['zbbu'] <= 2)

					            {{ "Gizi Baik" }}

					        @elseif($data['zbbu'] > 2)

					            {{ "Gizi Lebih" }}

					        @endif

					    </td>
					</tr>

					<tr>
					    <td>TB / U</td>
					    <td> : </td>
					    <td>{{ $data['ztbu'] }} |

					        @if($data['ztbu'] < -3)

					            {{ "Sangat Pendek" }}

					        @elseif($data['ztbu'] >= -3 && $data['ztbu'] < -2)

					            {{ "Pendek" }}

					        @elseif($data['ztbu'] >= -2 && $data['ztbu'] <= 2)

					            {{ "Normal" }}

					        @elseif($data['ztbu'] > 2)

					            {{ "Tinggi" }}

					        @endif

					    </td>
					</tr>

					<tr>
					    <td>BB / TB</td>
					    <td> : </td>
					    <td>{{ $data['zbbtb'] }} |

					        @if($data['zbbtb'] < -3)

					            {{ "Sangat Kurus" }}

					        @elseif($data['zbbtb'] >= -3 && $data['zbbtb'] < -2)

					            {{ "Kurus" }}

					        @elseif($data['zbbtb'] >= -2 && $data['zbbtb'] <= 2)

					            {{ "Normal" }}

					        @elseif($data['zbbtb'] > 2)

					            {{ "Gemuk" }}

					        @endif


				<tr>
					<td>Kebutuhan energi / hari</td>
					<td>:</td>
					<td>{{ "Belum" }} kkal</td>
				</tr>

				<tr>
					<td>Kebutuhan protein / hari</td>
					<td>:</td>
					<td>{{ "belum" }} gr</td>
				</tr>

					    </td>
					</tr>

					<tr>
						<td colspan="3">
							<hr>
						</td>
					</tr>

			</tbody>
		</table>

	@else

		{{ "Tidak ada riwayat pemeriksaan" }}

	@endif
	
</body>
</html>