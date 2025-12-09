@if ($histori->isEmpty())
    <div class="alert alert-outline-warning">
        <p>Data Belum Ada</p>
    </div>
@else
    <ul class="listview image-listview">
        @foreach ($histori as $d)
        <li>
            <div class="item">
                @php
                    $path = Storage::url('uploads/absensi/'.$d->foto_in);
                @endphp
                <img src="{{ url($path) }}" alt="image" class="image">
                
                <div class="in">
                    <div style="text-align: left;">
                        <b>{{ date("d-m-Y",strtotime($d->tgl_presensi)) }}</b>
                    </div>
                    
                    <span class="badge {{ $d->jam_in < '07:00' ? 'bg-success' : 'bg-danger' }}">
                        {{ $d->jam_in }}
                    </span>
                    
                    <div style="position: relative; display: flex; align-items: center; justify-content: center;">
                        
                        <span class="badge bg-primary">
                            {{ $d->jam_out }}
                        </span>
                        
                        @if ($d->status_checkout == 'auto')
                            <span style="
                                position: absolute; 
                                top: 22px; 
                                left: 50%; 
                                transform: translateX(-50%); 
                                font-size: 0.55rem; 
                                color: #dc3545; 
                                white-space: nowrap;
                                font-weight: bold;
                                text-decoration: underline; /* Menambahkan garis bawah */
                            ">
                                [Checkout Otomatis] </span>
                        @endif

                    </div>
                </div>
            </div>
        </li>
        @endforeach
    </ul>
@endif