<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice #{{ $invoice->number }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 20px;
        }
        .invoice-header {
            border-bottom: 2px solid #000;
            margin-bottom: 20px;
            text-align: center;
        }
        .invoice-details {
            width: 100%;
            border-collapse: collapse;
        }
        .invoice-details th,
        .invoice-details td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        .invoice-footer {
            border-top: 2px solid #000;
            margin-top: 20px;
            text-align: center;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <div class="invoice-header">
        <h1>Invoice #{{ $invoice->number }}</h1>
        <p>Issued: {{ $invoice->created_at->format('Y-m-d') }}</p>
        <p>Due Date: {{ $invoice->due_date->format('Y-m-d') }}</p>
    </div>
    <div class="invoice-content">
        <h3>Bill To:</h3>
        <p>{{ $invoice->client->name }}</p>
        <!-- Add additional client details if available -->
        
        <h3>Invoice Summary</h3>
        <table class="invoice-details">
            <tr>
                <th>Subtotal</th>
                <th>Tax</th>
                <th>Total</th>
            </tr>
            <tr>
                <td>{{ $invoice->subtotal }}</td>
                <td>{{ $invoice->tax }}</td>
                <td>{{ $invoice->total }}</td>
            </tr>
        </table>
        <p><strong>Status:</strong> {{ $invoice->status }}</p>
        <p><strong>Payment Terms:</strong> {{ $invoice->payment_terms }}</p>
        @if($invoice->notes)
            <p><strong>Notes:</strong> {{ $invoice->notes }}</p>
        @endif
    </div>
    <div class="invoice-footer">
        <p>Thank you for your business!</p>
    </div>
</body>
</html>
