import './bootstrap';
import ChartComponent from './components/ChartComponent';
app.component('chart-component', ChartComponent);
import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();
