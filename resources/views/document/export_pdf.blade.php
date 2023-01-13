<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>eNabavka.ba</title>
    <style type="text/css">
        body {
            font-size: 13px;
            line-height: 20px;
            font-family: DejaVu Sans !important;
            color: #58585A;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

       td,th {
           border-bottom: 1px solid #58585A;
           line-height: 1;
       }

        .table thead tr th {
            text-transform: uppercase;
            font-weight: 600;
            font-size: 11px;
            padding-top: 14px;
            padding-bottom: 14px;
            vertical-align: middle;
            text-align: left;
        }

        .table td {
            font-size: 10px;
            color: #58585A;
            padding: 5px 0;
        }
    </style>
</head>
<body>
    <table>
        <thead>
            <tr>
                <th>Broj</th>
                <th>Datum</th>
                <th>Tip</th>
                <th>Klijent</th>
                <th>Država</th>
                <th>Status</th>
                <th>Iznos</th>
                <th>Komercijalista</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $id => $item)
            <tr>
                <td>{{ $item->id }}</td>
                <td>{{ date('d.m.Y.', strtotime($item->date_of_order)) }}</td>
                <td>{{  $item->rType->name }}</td>
                <td>{{ is_null($item->rClient) ? '-' : $item->rClient->full_name }}</td>
                <td>{{ is_null($item->rClient) ? '-' : $item->rClient->rCountry->name }}</td>
                <td>{{  $item->rStatus->name }} </td>
                <td>{{ format_price($item->total_value) }}</td>
                <td>{{ is_null($item->rCreatedBy) ? '-' : (is_null($item->rCreatedBy->rPerson) ? $item->rCreatedBy->email : $item->rCreatedBy->rPerson->name ) }}</td>
            </tr>
            @endforeach
    </tbody>
</table>
</body>
</html>
