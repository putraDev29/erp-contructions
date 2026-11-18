import './bootstrap';
import Alpine from 'alpinejs';
import Chart from 'chart.js/auto';
import { createIcons, icons } from 'lucide';

window.Alpine = Alpine;
window.Chart = Chart;
window.createLucideIcons = () => {
    createIcons({ icons });
};

Alpine.start();

document.addEventListener('DOMContentLoaded', () => {
    window.createLucideIcons();
});
