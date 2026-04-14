// resources/js/app.js
import Alpine from 'alpinejs';
import persist from '@alpinejs/persist';
import flatpickr from 'flatpickr';
import Dropzone from 'dropzone';
import 'jsvectormap';
import 'jsvectormap/dist/maps/world.js';

// Chart imports
import ApexCharts from 'apexcharts';

// Register Alpine plugins
Alpine.plugin(persist);

// Make Alpine available globally
window.Alpine = Alpine;

// Initialize Alpine
Alpine.start();

// Initialize global components when DOM is ready
document.addEventListener('DOMContentLoaded', () => {
    // Initialize flatpickr for datepickers
    document.querySelectorAll('.datepicker').forEach(element => {
        flatpickr(element, {
            mode: "range",
            dateFormat: "Y-m-d",
            allowInput: true,
        });
    });

    // Initialize tooltips
    initTooltips();

    // Initialize modals
    initModals();
});

// Tooltip handler
function initTooltips() {
    const tooltips = document.querySelectorAll('[data-tooltip]');
    tooltips.forEach(element => {
        element.addEventListener('mouseenter', (e) => {
            const tooltip = document.createElement('div');
            tooltip.className = 'absolute z-50 px-2 py-1 text-xs text-white bg-gray-900 rounded shadow-lg whitespace-nowrap';
            tooltip.textContent = element.dataset.tooltip;
            tooltip.style.top = `${e.clientY - 30}px`;
            tooltip.style.left = `${e.clientX}px`;
            document.body.appendChild(tooltip);

            element.addEventListener('mouseleave', () => tooltip.remove(), { once: true });
        });
    });
}

// Modal handler
function initModals() {
    window.openModal = (modalId) => {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }
    };

    window.closeModal = (modalId) => {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    };
}

// Export for use in components
export default { Alpine };
