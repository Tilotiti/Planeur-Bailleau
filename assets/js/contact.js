import 'leaflet/dist/leaflet.css'
import * as L from 'leaflet'
import iconRetinaUrl from '../images/marker-icon-2x.png'
import iconUrl from '../images/marker-icon.png'
import shadowUrl from '../images/marker-shadow.png'

const place = [48.51877643151166, 1.6406279947423663]

delete L.Icon.Default.prototype._getIconUrl

L.Icon.Default.mergeOptions({
    iconRetinaUrl: iconRetinaUrl,
    iconUrl: iconUrl,
    shadowUrl: shadowUrl,
});

const map = L.map('map', {
    layers: L.tileLayer('http://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png'),
    center: place,
    zoom: 9
})

L.marker(place)
    .addTo(map)
    .bindPopup("<b>CVVE Bailleau</b><br>28320 Bailleau-Armenonville<br>France")
    .openPopup()


document.getElementById("form_contact").addEventListener('submit', function(e) {
    e.preventDefault();
    grecaptcha.ready(function() {
        grecaptcha.execute('6LedSDcaAAAAAKPyWy6RMngaFbi5HZzFfczrqJhE', {action: 'submit'}).then(function(token) {
            document.getElementById("form_contact").submit();
        });
    });
})