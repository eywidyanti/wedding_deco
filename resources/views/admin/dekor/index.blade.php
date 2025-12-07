@extends('admin.layout')

@section('content')
    <!-- dekorasi start -->
    <section class="paketdekorasi">
        @if ($message = Session::get('success'))
            <div class="notifikasi success">
                <p>{{ $message }}</p>
            </div>
        @endif

        <h2>Tambah<span> Dekorasi</span></h2>
        <a href="{{ route('dekor.create') }}"> <button class="btn"><i class="fa fa-plus"></i> Tambah Dekorasi</button></a>
    </section>

    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif


    <div id="data-container" class="table-container">
        <table class="table">
            <thead>
                <tr>
                    <th>No</th>
                    {{-- <th>User</th> --}}
                    <th>Kategori</th>
                    {{-- <th>Gaya</th>
                    <th>Tema</th>
                    <th>Warna</th> --}}
                    <th>Nama</th>
                    <th>Gambar</th>
                    <th>Harga</th>
                    <th>Deskripsi</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($dekors as $dekor)
                    <tr>
                        <td>{{ ++$i }}</td>
                        {{-- <td>{{ $dekor->user->name }}</td> --}}
                        <td>{{ $dekor->kategori->nama_kategori ?? '-'}}</td>
                        {{-- <td>{{ $dekor->gaya }}</td>
                        <td>{{ $dekor->tema }}</td>
                        <td>{{ $dekor->warna }}</td> --}}
                        <td>{{ $dekor->nama }}</td>
                        <td><img src="img/admin/gambarDekor/{{ $dekor->gambar }}" width="100px"></td>
                        <td>{{ $dekor->harga }}</td>
                        <td>{{ $dekor->deskripsi }}</td>
                        <td>
                            <form action="{{ route('dekor.destroy', $dekor->id) }}" method="POST">
                                <a href="{{ route('dekor.edit', $dekor->slug) }}" type="button" class="aksi-btn"><i
                                        class="fa fa-pencil"></i></a>
                                <a href="{{ route('dekor.show', $dekor->slug) }}" type="button" class="aksi-btn2"><i
                                        class="fa fa-eye"></i></a>
                                @csrf
                                @method('DELETE')

                                <button type="submit" class="aksi-btn1" onclick="confirmDelete(event)"><i
                                        class="fa fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @if ($dekors->hasPages())
            <div class="pagination">
                {{-- Previous Page Link --}}
                <div class="pagination-item">
                    @if ($dekors->onFirstPage())
                        <span class="pagination-disabled"><i class="fa fa-chevron-left"></i> Prev</span>
                    @else
                        <a href="{{ $dekors->previousPageUrl() }}" rel="prev" class="pagination-link"><i
                                class="fa fa-chevron-left"></i> Prev</a>
                    @endif
                </div>

                {{-- Show page numbers --}}
                <div class="pagination-numbers">
                    @for ($i = max(1, $dekors->currentPage() - 2); $i <= min($dekors->lastPage(), $dekors->currentPage() + 2); $i++)
                        <a href="{{ $dekors->url($i) }}"
                            class="pagination-number @if ($i == $dekors->currentPage()) active @endif">{{ $i }}</a>
                    @endfor
                </div>

                {{-- Next Page Link --}}
                <div class="pagination-item">
                    @if ($dekors->hasMorePages())
                        <a href="{{ $dekors->nextPageUrl() }}" rel="next" class="pagination-link">Next <i
                                class="fa fa-chevron-right"></i></a>
                    @else
                        <span class="pagination-disabled">Next <i class="fa fa-chevron-right"></i></span>
                    @endif
                </div>
            </div>
        @endif
    </div>
    <!-- dekorasi end -->
@endsection
