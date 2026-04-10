<!DOCTYPE html>
<html>
<head>
    <title>Fault Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-4">

    <h2 class="mb-4">Fault List</h2>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Reference</th>
                <th>Title</th>
                <th>Category</th>
                <th>Location</th>
                <th>Description</th>
                <th>Incident Time</th>
                <th>People Involved</th>
            </tr>
        </thead>

        <tbody>
            @php
                $i = 1;
            @endphp
            @foreach($faults as $fault)
                <tr>
                    <!-- <td>{{ $fault->id }}</td> -->
                     <td>{{ $i }}</td>
                    <td>{{ $fault->fault_reference }}</td>
                    <td>{{ $fault->incident_title }}</td>
                    <td>{{ $fault->category->name ?? 'N/A' }}</td>

                    <td>
                        Lat: {{ $fault->lat }} <br>
                        Long: {{ $fault->long }}
                    </td>

                    <td>{{ $fault->description ?? 'N/A' }}</td>

                    <td>
                        {{ \Carbon\Carbon::parse($fault->incident_time)->format('d M Y H:i') }}
                    </td>

                    <td>
                        @if($fault->people->count() > 0)
                            <ul class="mb-0">
                                @foreach($fault->people as $person)
                                    <li>
                                        {{ $person->name }} ({{ $person->type }})
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <span class="text-muted">No people</span>
                        @endif
                    </td>
                </tr>
                @php
                    $i++;
                @endphp
            @endforeach
        </tbody>
    </table>

    {{-- Pagination --}}
    <div class="d-flex justify-content-center">
        {{ $faults->links() }}
    </div>

</div>

</body>
</html>