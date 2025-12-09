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
                        
                        @if ($d->jam_out != null && $d->jam_out != "00:00:00")
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
                                    text-decoration: underline;
                                ">
                                    [Checkout Otomatis]
                                </span>
                            @endif
                        
                        @else
                            <span class="badge bg-warning">
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