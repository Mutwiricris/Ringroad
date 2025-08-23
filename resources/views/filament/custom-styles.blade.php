<style>
/* Ring Road Cosmetics - Custom Filament Styling */

/* Reduce spacing between navbar and main content */
.fi-main {
    padding-top: 0.5rem !important;
}

.fi-main-content {
    padding-top: 0.25rem !important;
}

/* Compact header */
.fi-header {
    margin-bottom: 0.75rem !important;
    padding-bottom: 0.25rem !important;
}

/* Widget grid responsive improvements */
.fi-wi-stats-overview {
    gap: 0.75rem !important;
    margin-bottom: 1rem !important;
}

/* Mobile responsiveness */
@media (max-width: 768px) {
    .fi-main {
        padding: 0.25rem !important;
    }

    .fi-main-content {
        padding: 0.125rem !important;
    }

    .fi-wi-stats-overview {
        grid-template-columns: 1fr !important;
        gap: 0.5rem !important;
    }

    .fi-ta-table {
        font-size: 0.75rem !important;
    }

    .fi-ta-header-cell,
    .fi-ta-cell {
        padding: 0.375rem 0.25rem !important;
    }

    /* Mobile navigation improvements */
    .fi-topbar {
        position: sticky !important;
        top: 0 !important;
        z-index: 40 !important;
        background: white !important;
        border-bottom: 1px solid rgb(229 231 235) !important;
    }

    /* Better mobile menu button */
    .fi-sidebar-open-btn {
        padding: 0.5rem !important;
        margin-right: 0.5rem !important;
    }

    /* Improve mobile touch targets */
    .fi-btn {
        min-height: 44px !important;
        padding: 0.5rem 1rem !important;
    }

    /* Mobile-friendly dropdowns */
    .fi-dropdown-panel {
        max-width: calc(100vw - 2rem) !important;
        left: 1rem !important;
        right: 1rem !important;
    }
}

/* Tablet responsiveness */
@media (min-width: 769px) and (max-width: 1024px) {
    .fi-wi-stats-overview {
        grid-template-columns: repeat(2, 1fr) !important;
    }
}

/* Desktop responsiveness */
@media (min-width: 1025px) {
    .fi-wi-stats-overview {
        grid-template-columns: repeat(4, 1fr) !important;
    }
}

/* Compact widget cards */
.fi-wi-stats-overview-stat-card {
    padding: 0.875rem !important;
}

/* Better table spacing */
.fi-ta-content {
    margin-top: 0.5rem !important;
}

/* Sidebar improvements */
.fi-sidebar {
    border-right: 1px solid rgb(229 231 235);
}

/* Page title improvements */
.fi-page-heading {
    margin-bottom: 0.75rem !important;
}

/* Action buttons compact spacing */
.fi-ac-button-group {
    gap: 0.375rem !important;
}

/* Filter section compact */
.fi-ta-filters {
    padding: 0.5rem !important;
    margin-bottom: 0.75rem !important;
}

/* Chart widget spacing */
.fi-wi-chart {
    margin-bottom: 1rem !important;
}

/* Table widget full width */
.fi-wi-table {
    width: 100% !important;
}

/* Notification positioning */
.fi-no-notifications {
    top: 3.5rem !important;
}

/* Brand name styling */
.fi-logo {
    font-weight: 600 !important;
    color: rgb(219 39 119) !important; /* Pink color for cosmetics brand */
}

/* Custom scrollbar for better UX */
.fi-sidebar-nav::-webkit-scrollbar {
    width: 4px;
}

.fi-sidebar-nav::-webkit-scrollbar-track {
    background: transparent;
}

.fi-sidebar-nav::-webkit-scrollbar-thumb {
    background: rgb(156 163 175);
    border-radius: 2px;
}

.fi-sidebar-nav::-webkit-scrollbar-thumb:hover {
    background: rgb(107 114 128);
}

/* Additional mobile navigation enhancements */
@media (max-width: 480px) {
    /* Extra small screens (phones in portrait) */
    .fi-logo {
        font-size: 0.875rem !important;
        max-width: 150px !important;
    }

    .fi-topbar {
        padding: 0.375rem 0.75rem !important;
    }

    .fi-main {
        padding: 0.125rem !important;
    }

    .fi-main-content {
        padding: 0.0625rem !important;
    }

    /* Smaller action buttons on very small screens */
    .fi-btn {
        font-size: 0.875rem !important;
        padding: 0.375rem 0.75rem !important;
    }
}
</style>
