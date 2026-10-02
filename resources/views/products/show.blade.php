<!DOCTYPE html>
<html>
<head>
    <title>Data Produk</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 30px;
        }

        h1 {
            font-size: 32px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            border: 1px solid black;
            padding: 10px;
            text-align: left;
        }

        th {
            text-align: center;
        }

        img {
            width: 240px;
            height: 240px;
            object-fit: cover;
            border-radius: 12px;
        }
    </style>
</head>
<body>

    <h1>Data Produk</h1>

    <table>
        <thead>
            <tr>
                <th>Name</th>
                <th>Category</th>
                <th>Description</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Image</th>
                <th>Is Active</th>
            </tr>
        </thead>

        <tbody>
            <tr>
                <td>{{ $product->name }}</td>
                <td>{{ $product->category }}</td>
                <td>{{ $product->description }}</td>
                <td>{{ number_format($product->price, 2) }}</td>
                <td>{{ $product->stock }}</td>

                <td>
                    @if($product->image_url)
                        <img src="{{ $product->image_url }}" alt="Gambar {{ $product->name }}">
                    @else
                        Tidak ada gambar
                    @endif
                </td>

                <td>
                    {{ $product->is_active ? 'Yes' : 'No' }}
                </td>
            </tr>
        </tbody>
    </table>

</body>
</html>