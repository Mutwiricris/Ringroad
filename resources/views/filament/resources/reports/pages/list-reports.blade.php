<x-filament-panels::page>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Inventory Report Card -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center">
                    <div class="p-2 bg-green-100 rounded-lg mr-3">
                        <x-heroicon-o-archive-box class="w-6 h-6 text-green-600" />
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Inventory Report</h3>
                        <p class="text-sm text-gray-500">Track stock levels across locations</p>
                    </div>
                </div>
            </div>
            <p class="text-gray-600 mb-4">Generate detailed inventory reports with filtering by date range, category, and location. View stock levels, values, and distribution.</p>
            <a href="{{ \App\Filament\Resources\Reports\ReportResource::getUrl('inventory-report') }}" 
               class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                <x-heroicon-o-chart-bar class="w-4 h-4 mr-2" />
                Generate Report
            </a>
        </div>

        <!-- Profit Margin Report Card -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center">
                    <div class="p-2 bg-blue-100 rounded-lg mr-3">
                        <x-heroicon-o-currency-dollar class="w-6 h-6 text-blue-600" />
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900">Profit Margin Report</h3>
                        <p class="text-sm text-gray-500">Analyze profitability by product</p>
                    </div>
                </div>
            </div>
            <p class="text-gray-600 mb-4">Analyze profit margins across your product catalog. Filter by category, margin thresholds, and view potential profits.</p>
            <a href="{{ \App\Filament\Resources\Reports\ReportResource::getUrl('profit-margin-report') }}" 
               class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                <x-heroicon-o-chart-bar class="w-4 h-4 mr-2" />
                Generate Report
            </a>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="mt-8">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Overview</h3>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                <div class="text-2xl font-bold text-gray-900">{{ \App\Models\ProductVariant::count() }}</div>
                <div class="text-sm text-gray-500">Total Products</div>
            </div>
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                <div class="text-2xl font-bold text-gray-900">{{ \App\Models\Category::count() }}</div>
                <div class="text-sm text-gray-500">Categories</div>
            </div>
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                <div class="text-2xl font-bold text-gray-900">{{ \App\Models\Location::count() }}</div>
                <div class="text-sm text-gray-500">Locations</div>
            </div>
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                <div class="text-2xl font-bold text-gray-900">{{ \App\Models\Inventory::sum('quantity') }}</div>
                <div class="text-sm text-gray-500">Total Stock Units</div>
            </div>
        </div>
    </div>
</x-filament-panels::page>
