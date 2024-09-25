<!DOCTYPE html>
<html>
<head>
    <title>Inventory Low Notification</title>
</head>
<body>
    <h1>Notification: Low Inventory</h1>
    <p>The stock for the following inventory items is running low:</p>

    <table border="1" cellpadding="10" cellspacing="0">
        <thead>
            <tr>
                <th>Asset Code</th>
                <th>Item Name</th>
                <th>Remaining Quantity</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($details['items'] as $item)
                <tr>
                    <td>{{ $item['code'] }}</td>
                    <td>{{ $item['name'] }}</td>
                    <td>{{ $item['qty'] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <p>Please take necessary actions to restock these items.</p>

    <p>Thank you,</p>
    <p>Inventory Management System</p>
</body>
</html>
