<x-filament-panels::page>
    <style>
        .report-container {
            display: flex;
            min-height: 100vh;
            background-color: #f9fafb;
        }
        .main-content {
            flex: 1;
            padding: 24px;
        }
        .report-header {
            margin-bottom: 32px;
        }
        .header-icons {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 16px;
        }
        .icon-container {
            display: flex;
            align-items: center;
            gap: 16px;
        }
        .icon-box {
            width: 48px;
            height: 48px;
            background-color: #059669;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .company-header {
            text-align: center;
            margin-bottom: 32px;
        }
        .company-name {
            font-size: 20px;
            font-weight: bold;
            color: #111827;
            margin-bottom: 8px;
        }
        .report-title {
            font-size: 18px;
            font-weight: 600;
            color: #374151;
        }
        .section-title {
            font-size: 18px;
            font-weight: 600;
            color: #111827;
            margin-bottom: 16px;
            padding-bottom: 8px;
            border-bottom: 2px solid #7c3aed;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 16px;
            margin-bottom: 24px;
        }
        .stat-item {
            text-align: center;
        }
        .stat-label {
            font-size: 14px;
            font-weight: 500;
            color: #6b7280;
            margin-bottom: 4px;
        }
        .stat-value {
            font-size: 18px;
            font-weight: bold;
            color: #111827;
        }
        .table-container {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
        }
        .table-header {
            background-color: #f9fafb;
        }
        .table-header th {
            padding: 12px 24px;
            text-align: left;
            font-size: 12px;
            font-weight: 500;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .table-row {
            border-bottom: 1px solid #e5e7eb;
        }
        .table-row:hover {
            background-color: #f9fafb;
        }
        .table-cell {
            padding: 16px 24px;
            font-size: 14px;
            color: #111827;
        }
        .product-name {
            font-weight: 500;
            color: #2563eb;
        }
        .status-badge {
            display: inline-flex;
            padding: 4px 8px;
            font-size: 12px;
            font-weight: 600;
            border-radius: 9999px;
        }
        .status-in-stock {
            background-color: #dcfce7;
            color: #166534;
        }
        .status-low-stock {
            background-color: #fef2f2;
            color: #991b1b;
        }
        .filters-sidebar {
            width: 320px;
            background-color: white;
            box-shadow: -1px 0 3px rgba(0, 0, 0, 0.1);
            border-left: 1px solid #e5e7eb;
        }
        .sidebar-content {
            padding: 24px;
        }
        .sidebar-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 24px;
        }
        .sidebar-title {
            font-size: 18px;
            font-weight: 600;
            color: #111827;
        }
        .filter-button {
            width: 100%;
            background-color: #7c3aed;
            color: white;
            font-weight: 500;
            padding: 8px 16px;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: background-color 0.2s;
        }
        .filter-button:hover {
            background-color: #6d28d9;
        }
        .empty-state {
            text-align: center;
            padding: 32px;
            color: #6b7280;
        }
    </style>

    <div class="report-container">
        <!-- Main Content Area -->
        <div class="main-content">
            <!-- Report Header -->
            <div class="report-header">
                <div class="header-icons">
                    <div class="icon-container">
                        <div class="icon-box">
                            <svg width="24" height="24" fill="none" stroke="white" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M9 5l7 7-7 7"></path>
                            </svg>
                        </div>
                        <h1 style="font-size: 24px; font-weight: bold; color: #111827; margin: 0;">Inventory Report</h1>
                    </div>
                    <div class="icon-box">
                        <svg width="24" height="24" fill="none" stroke="white" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M9 5l7 7-7 7"></path>
                        </svg>
                    </div>
                </div>
                
                <div class="company-header">
                    <h2 class="company-name">RING ROAD COSMETICS</h2>
                    <h3 class="report-title">Inventory Report</h3>
                </div>
            </div>

            @if($summaryStats)
            <!-- Summary Statistics -->
            <div style="margin-bottom: 32px;">
                <h3 class="section-title">Summary Statistics</h3>
                
                <div class="stats-grid">
                    @foreach($this->getStats() as $stat)
                    <div class="stat-item">
                        <div class="stat-label">{{ $stat->getLabel() }}</div>
                        <div class="stat-value">{{ $stat->getValue() }}</div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Detailed Listing -->
            <div style="margin-bottom: 32px;">
                <h3 class="section-title">Detailed Listing</h3>
                
                @if($reportData && $reportData->count() > 0)
                <div class="table-container">
                    <table class="data-table">
                        <thead class="table-header">
                            <tr>
                                <th>Product</th>
                                <th>Category</th>
                                <th>Stock</th>
                                <th>Location</th>
                                <th>Cost Price</th>
                                <th>Status</th>
                                <th>Manager</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($reportData as $item)
                            <tr class="table-row">
                                <td class="table-cell product-name">{{ $item->productVariant->product->name }}</td>
                                <td class="table-cell">{{ $item->productVariant->product->category->name ?? 'Uncategorized' }}</td>
                                <td class="table-cell">{{ number_format($item->quantity) }}</td>
                                <td class="table-cell">{{ $item->location->name ?? 'Unknown' }}</td>
                                <td class="table-cell">${{ number_format($item->productVariant->cost_price, 2) }}</td>
                                <td class="table-cell">
                                    <span class="status-badge {{ $item->quantity > 10 ? 'status-in-stock' : 'status-low-stock' }}">
                                        {{ $item->quantity > 10 ? 'IN STOCK' : 'LOW STOCK' }}
                                    </span>
                                </td>
                                <td class="table-cell">-</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @else
                <div class="empty-state">
                    <p>No inventory data found for the selected filters.</p>
                </div>
                @endif
            </div>
            @endif
        </div>

        <!-- Filters Sidebar -->
        <div class="filters-sidebar">
            <div class="sidebar-content">
                <div class="sidebar-header">
                    <h3 class="sidebar-title">Filters</h3>
                    <svg width="20" height="20" fill="none" stroke="#9ca3af" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                </div>

                <div style="margin-bottom: 24px;">
                    {{ $this->form }}
                </div>
                
                <button type="button" wire:click="generateReport" class="filter-button">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.707A1 1 0 013 7V4z"></path>
                    </svg>
                    <span>Filter</span>
                </button>
            </div>
        </div>
    </div>
</x-filament-panels::page>
