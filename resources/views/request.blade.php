<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fluent Design Table</title>
    <style>
        /* Fluent Design-inspired styles */
        .fluent-table {
            border-collapse: collapse;
            width: 100%;
        }

        .fluent-table th, .fluent-table td {
            border: 1px solid #e0e0e0;
            padding: 10px;
            text-align: left;
        }

        .fluent-table th {
            background-color: #0078D4;
            color: white;
        }

        .fluent-table tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .fluent-table tr:hover {
            background-color: #e5f2ff;
        }
    </style>
</head>
<body>
<table class="fluent-table">
    <thead>
    <tr>
        <th>No. </th>
        <th>Recipe</th>
        <th>Requested By</th>
    </tr>
    </thead>
    <tbody>
        @foreach($recipes as $recipe)
        <tr>
            <td>{{ $recipe->id }}</td>
            <td>{{ $recipe->name }}</td>
            <td>{{ $recipe->user_name }}</td>
        @endforeach

    </tbody>
</table>
</body>
</html>
