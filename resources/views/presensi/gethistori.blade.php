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
                    <div style="text-align: left; flex-grow: 1;">
                        <b style="font-size: 0.85rem;">{{ date("d-m-Y",strtotime($d->tgl_presensi)) }}</b>
                    </div>
                    
                    <div style="min-width: 70px; text-align: center;">
                        <span class="badge {{ $d->jam_in < '07:00' ? 'bg-success' : 'bg-danger' }}" 
                              style="font-size: 0.75rem; width: 100%; padding: 5px 0; display: inline-block;">
                            {{ $d->jam_in }}
                        </span>
                    </div>
                    
                    <div style="min-width: 70px; text-align: center; margin-left: 5px; position: relative; display: flex; justify-content: center; flex-direction: column;">
                        
                        @if ($d->jam_out != null && $d->jam_out != "00:00:00")
                            <span class="badge bg-primary" 
                                  style="font-size: 0.75rem; width: 100%; padding: 5px 0; display: inline-block;">
                                {{ $d->jam_out }}
                            </span>
                            
                            @if ($d->status_checkout == 'auto')
                                <span style="
                                    position: absolute; 
                                    top: 25px; /* Sesuaikan jarak ke bawah */
                                    left: 50%; 
                                    transform: translateX(-50%); 
                                    font-size: 0.5rem; /* Ukuran font diperkecil agar rapi */
                                    color: #dc3545; 
                                    white-space: nowrap;
                                    font-weight: bold;
                                    text-decoration: underline;
                                    z-index: 10;
                                ">
                                    [Checkout Otomatis]
                                </span>
                            @endif
                        
                        @else
                            <span class="badge bg-warning" 
                                  style="font-size: 0.7rem; width: 100%; padding: 5px 0; display: inline-block;">
                                Belum Pulang
                            </span>
                        @endif

                    </div>
                </div>
            </div>
        </li>
        @endforeach
    </ul>
@endif