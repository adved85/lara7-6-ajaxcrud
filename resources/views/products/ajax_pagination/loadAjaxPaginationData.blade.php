<div id="load" style="position: relative;">
    <table class="table table-bordered table-condenced table-sm">
        <thead class="thead-light">
        <tr>
            <th scope="col">ID</th>
            <th scope="col">Name</th>
            <th scope="col">Detail</th>
        </tr>
        </thead>
        <tbody>
        @foreach($data as $item)
            <tr>
                <td width="50px">
                {{$item->id}}</th>
                <td>{{ $item->name }}</td>
                <td>{{ $item->detail }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
{!! $data->render() !!}
