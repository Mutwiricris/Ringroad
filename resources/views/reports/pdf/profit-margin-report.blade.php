<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Profit Margin Report - Ring Road Cosmetics</title>
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
            font-size: 18px;
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
            padding: 10px 6px;
            text-align: left;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .table td {
            padding: 8px 6px;
            border-bottom: 1px solid #eee;
            font-size: 10px;
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
        .status-profitable {
            background-color: #d4edda;
            color: #155724;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
        }
        .status-low-margin {
            background-color: #f8d7da;
            color: #721c24;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 9px;
            font-weight: bold;
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
        <div class="report-title">Profit Margin Report</div>
        <div class="report-date">Generated on {{ now()->format('F j, Y \a\t g:i A') }}</div>
    </div>

    <div class="filters">
        <h3>Report Filters</h3>
        <div class="filter-item"><strong>Date From:</strong> {{ $filters['date_from'] ? \Carbon\Carbon::parse($filters['date_from'])->format('M j, Y') : 'All Time' }}</div>
        <div class="filter-item"><strong>Date To:</strong> {{ $filters['date_to'] ? \Carbon\Carbon::parse($filters['date_to'])->format('M j, Y') : 'All Time' }}</div>
        <div class="filter-item"><strong>Category:</strong> {{ $filters['category_name'] ?? 'All Categories' }}</div>
        <div class="filter-item"><strong>Min Margin:</strong> {{ $filters['min_margin'] ? $filters['min_margin'].'%+' : 'All Margins' }}</div>
    </div>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-value">{{ $summaryStats['total_products'] }}</div>
            <div class="stat-label">Total Products</div>
        </div>
        <div class="stat-card">
            <div class="stat-value">{{ number_format($summaryStats['total_units_sold']) }}</div>
            <div class="stat-label">Units Sold</div>
        </div>
        <div class="stat-card">
            <div class="stat-value">${{ number_format($summaryStats['total_revenue'], 2) }}</div>
            <div class="stat-label">Total Revenue</div>
        </div>
        <div class="stat-card">
            <div class="stat-value">${{ number_format($summaryStats['total_cost'], 2) }}</div>
            <div class="stat-label">Total Cost</div>
        </div>
        <div class="stat-card">
            <div class="stat-value">${{ number_format($summaryStats['total_profit'], 2) }}</div>
            <div class="stat-label">Total Profit</div>
        </div>
        <div class="stat-card">
            <div class="stat-value">{{ number_format($summaryStats['average_margin'], 1) }}%</div>
            <div class="stat-label">Average Margin</div>
        </div>
    </div>

    @if($reportData && $reportData->count() > 0)
    <table class="table">
        <thead>
            <tr>
                <th>Product</th>
                <th>Category</th>
                <th class="text-right">Units Sold</th>
                <th class="text-right">Cost Price</th>
                <th class="text-right">Selling Price</th>
                <th class="text-right">Revenue</th>
                <th class="text-right">Cost</th>
                <th class="text-right">Profit</th>
                <th class="text-right">Margin %</th>
                <th class="text-center">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reportData as $item)
            <tr>
                <td>{{ $item->product_name }}</td>
                <td>{{ $item->category ?? 'Uncategorized' }}</td>
                <td class="text-right">{{ number_format($item->units_sold) }}</td>
                <td class="text-right">${{ number_format($item->cost_price, 2) }}</td>
                <td class="text-right">${{ number_format($item->selling_price, 2) }}</td>
                <td class="text-right">${{ number_format($item->total_revenue, 2) }}</td>
                <td class="text-right">${{ number_format($item->total_cost, 2) }}</td>
                <td class="text-right">${{ number_format($item->total_profit, 2) }}</td>
                <td class="text-right">{{ number_format($item->profit_margin, 1) }}%</td>
                <td class="text-center">
                    <span class="{{ $item->profit_margin > 20 ? 'status-profitable' : 'status-low-margin' }}">
                        {{ $item->profit_margin > 20 ? 'PROFITABLE' : 'LOW MARGIN' }}
                    </span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <div style="text-align: center; padding: 40px; color: #666;">
        <p>No profit margin data found for the selected filters.</p>
    </div>
    @endif

    <div class="footer">
        <p>Ring Road Cosmetics - Profit Margin Report | Page 1 of 1 | {{ now()->format('Y-m-d H:i:s') }}</p>
    </div>
</body>
</html>
