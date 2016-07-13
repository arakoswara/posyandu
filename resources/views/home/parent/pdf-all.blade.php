<h4>No. Registrasi : {{ $data_balita->no_reg }} | Nama Balita : {{ $data_balita->nama_balita }} | Tanggal Lahir : {{ $data_balita->tgl_lahir }}</h4>

<hr>

@if (!empty($data))

<table>
	

		<tbody>		

			@foreach ($data as $value)

				<tr>
					<td><h4>Tanggal periksa</h4></td>
					<td>:</td>
					<td><h4>{{ $value->periksa->tgl_periksa }}</h4></td>
				</tr>

				<tr>
					<td>Berat badan</td>
					<td>:</td>
					<td>{{ $value->periksa->berat_badan }} kg</td>
				</tr>

				<tr>
					<td>Tinggi badan</td>
					<td>:</td>
					<td>{{ $value->periksa->tinggi_badan }} gr</td>
				</tr>

				<tr>
				    <td>BB / U</td>
				    <td> : </td>
				    <td>{{ $value->zbbu }} |

				        @if($value->zbbu < -3)

<<<<<<< HEAD
			<tr>
				<td>Tinggi badan</td>
				<td>:</td>
				<td>{{ $value->periksa->tinggi_badan }} cm</td>
			</tr>
=======
				            {{ "Gizi Buruk" }}
>>>>>>> ca598e7ddff40acd6825b42600f849d77627f19f

				        @elseif($value->zbbu >= -3 && $value->zbbu < -2)

				            {{ "Gizi Kurang" }}

				        @elseif($value->zbbu >= -2 && $value->zbbu <= 2)

				            {{ "Gizi Baik" }}

				        @elseif($value->zbbu > 2)

				            {{ "Gizi Lebih" }}

				        @endif

				    </td>
				</tr>

				<tr>
				    <td>TB / U</td>
				    <td> : </td>
				    <td>{{ $value->ztbu }} |

				        @if($value->ztbu < -3)

				            {{ "Sangat Pendek" }}

				        @elseif($value->ztbu >= -3 && $value->ztbu < -2)

				            {{ "Pendek" }}

				        @elseif($value->ztbu >= -2 && $value->ztbu <= 2)

				            {{ "Normal" }}

				        @elseif($value->ztbu > 2)

				            {{ "Tinggi" }}

				        @endif

				    </td>
				</tr>

				<tr>
				    <td>BB / TB</td>
				    <td> : </td>
				    <td>{{ $value->zbbtb }} |

				        @if($value->zbbtb < -3)

				            {{ "Sangat Kurus" }}

				        @elseif($value->zbbtb >= -3 && $value->zbbtb < -2)

				            {{ "Kurus" }}

				        @elseif($value->zbbtb >= -2 && $value->zbbtb <= 2)

				            {{ "Normal" }}

				        @elseif($value->zbbtb > 2)

				            {{ "Gemuk" }}

				        @endif

				    </td>
				</tr>

				<tr>
					<td>Kebutuhan protein</td>
					<td>:</td>
					<td>{{ $value->protein }} kkal</td>
				</tr>

				<tr>
					<td>Kebutuhan energi</td>
					<td>:</td>
					<td>{{ $value->energi }} gr</td>
				</tr>

				<tr>
					<td colspan="3">
						<hr>
					</td>
				</tr>

<<<<<<< HEAD
			<tr>
				<td>Kebutuhan energi/ hari</td>
				<td>:</td>
				<td>{{ $value->energi }} Kkal</td>
			</tr>

			<tr>
				<td>Kebutuhan protein / hari</td>
				<td>:</td>
				<td>{{ $value->protein }} gr</td>
			</tr>
=======
			@endforeach

		</tbody>
>>>>>>> ca598e7ddff40acd6825b42600f849d77627f19f

</table>
	@else

		{{ "Tidak ada data pemeriksaan" }}

	@endif