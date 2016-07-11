<h4>No. Registrasi : {{ $data_balita->no_reg }} | Nama Balita : {{ $data_balita->nama_balita }} | Tanggal Lahir : {{ $data_balita->tgl_lahir }}</h4>

<hr>

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
				<td>{{ $value->periksa->berat_badan }}</td>
			</tr>

			<tr>
				<td>Tinggi badan</td>
				<td>:</td>
				<td>{{ $value->periksa->tinggi_badan }}</td>
			</tr>

			<tr>
				<td>BB / U</td>
				<td>:</td>
				<td>{{ $value->zbbu }}</td>
			</tr>

			<tr>
				<td>TB / U</td>
				<td>:</td>
				<td>{{ $value->ztbu }}</td>
			</tr>

			<tr>
				<td>BB / TB</td>
				<td>:</td>
				<td>{{ $value->zbbtb }}</td>
			</tr>

			<tr>
				<td>Kebutuhan protein</td>
				<td>:</td>
				<td>{{ $value->protein }}</td>
			</tr>

			<tr>
				<td>Kebutuhan energi</td>
				<td>:</td>
				<td>{{ $value->energi }}</td>
			</tr>

			<tr>
				<td colspan="3">
					<hr>
				</td>
			</tr>

		@endforeach

	</tbody>
</table>