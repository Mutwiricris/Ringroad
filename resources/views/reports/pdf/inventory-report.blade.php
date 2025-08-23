<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Inventory Report - Ring Road Cosmetics</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #e91e63;
            padding-bottom: 20px;
        }
        .company-name {
            font-size: 24px;
            font-weight: bold;
            color: #e91e63;
            margin-bottom: 5px;
        }
        .report-title {
            font-size: 18px;
            color: #666;
            margin-bottom: 10px;
        }
        .report-date {
            font-size: 12px;
            color: #999;
        }
        .filters {
            background-color: #f5f5f5;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .filters h3 {
            margin: 0 0 10px 0;
            font-size: 14px;
            color: #333;
        }
        .filter-item {
            display: inline-block;
            margin-right: 20px;
            margin-bottom: 5px;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            margin-bottom: 30px;
        }
        .stat-card {
            background-color: #f9f9f9;
            padding: 15px;
            border-radius: 5px;
            text-align: center;
            border-left: 4px solid #e91e63;
        }
        .stat-value {
            font-size: 20px;
            font-weight: bold;
            color: #333;
            margin-bottom: 5px;
        }
        .stat-label {
            font-size: 11px;
            color: #666;
            text-transform: uppercase;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .table th {
            background-color: #e91e63;
            color: white;
            padding: 10px 8px;
            text-align: left;
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .table td {
            padding: 8px;
            border-bottom: 1px solid #eee;
            font-size: 11px;
        }
        .table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #999;
            border-top: 1px solid #eee;
            padding-top: 15px;
        }
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="company-name">RING ROAD COSMETICS</div>
        <div class="report-title">Inventory Report</div>
        <div class="report-date">Generated on {{ now()->format('F j, Y \a\t g:i A') }}</div>
    </div>

    <div class="filters">
        <h3>Report Filters</h3>
        <div class="filter-item"><strong>Date From:</strong> {{ $filters['date_from'] ? \Carbon\Carbon::parse($filters['date_from'])->format('M j, Y') : 'All Time' }}</div>
        <div class="filter-item"><strong>Date To:</strong> {{ $filters['date_to'] ? \Carbon\Carbon::parse($filters['date_to'])->format('M j, Y') : 'All Time' }}</div>
        <div class="filter-item"><strong>Category:</strong> {{ $filters['category_name'] ?? 'All Categories' }}</div>
        <div class="filter-item"><strong>Location:</strong> {{ $filters['location_name'] ?? 'All Locations' }}</div>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-value">{{ number_format($summaryStats['total_items']) }}</div>
            <div class="stat-label">Total Items</div>
        </div>
        <div class="stat-card">
            <div class="stat-value">{{ number_format($summaryStats['total_quantity']) }}</div>
            <div class="stat-label">Total Quantity</div>
        </div>
        <div class="stat-card">
            <div class="stat-value">{{ $summaryStats['total_locations'] }}</div>
            <div class="stat-label">Locations</div>
        </div>
        <div class="stat-card">
            <div class="stat-value">{{ $summaryStats['categories'] }}</div>
            <div class="stat-label">Categories</div>
        </div>
        <div class="stat-card">
            <div class="stat-value">${{ number_format($summaryStats['total_value_cost'], 2) }}</div>
            <div class="stat-label">Cost Value</div>
        </div>
        <div class="stat-card">
            <div class="stat-value">${{ number_format($summaryStats['total_value_selling'], 2) }}</div>
            <div class="stat-label">Selling Value</div>
        </div>
    </div>

    @if($reportData && $reportData->count() > 0)
    <table class="table">
        <thead>
            <tr>
                <th>Product</th>
                <th>SKU</th>
                <th>Category</th>
                <th>Location</th>
                <th class="text-right">Quantity</th>
                <th class="text-right">Cost Price</th>
                <th class="text-right">Selling Price</th>
                <th class="text-right">Total Cost</th>
                <th class="text-right">Total Value</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reportData as $item)
            <tr>
                <td>{{ $item->productVariant->product->name ?? 'N/A' }}</td>
                <td>{{ $item->productVariant->sku ?? 'N/A' }}</td>
                <td>{{ $item->productVariant->product->category->name ?? 'Uncategorized' }}</td>
                <td>{{ $item->location->name ?? 'N/A' }}</td>
                <td class="text-right">{{ number_format($item->quantity) }}</td>
                <td class="text-right">${{ number_format($item->productVariant->cost_price, 2) }}</td>
                <td class="text-right">${{ number_format($item->productVariant->selling_price, 2) }}</td>
                <td class="text-right">${{ number_format($item->quantity * $item->productVariant->cost_price, 2) }}</td>
                <td class="text-right">${{ number_format($item->quantity * $item->productVariant->selling_price, 2) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <div style="text-align: center; padding: 40px; color: #666;">
        <p>No inventory data found for the selected filters.</p>
    </div>
    @endif

    <div class="footer">
        <p>Ring Road Cosmetics - Inventory Report | Page 1 of 1 | {{ now()->format('Y-m-d H:i:s') }}</p>
    </div>
</body>
</html>
