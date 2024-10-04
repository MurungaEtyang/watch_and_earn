async function checkUserLocation() {
    try {
        const response = await fetch('https://ipapi.co/json/');
        const data = await response.json();
        return data; 
    } catch (error) {
        console.error('Error fetching location:', error);
        return null; 
    }
}

async function init() {
    const locationData = await checkUserLocation();

    document.getElementById('locationLoader').classList.remove('d-none');

    if (locationData && locationData.country) {
        const supportedCountries = {
            'US': '+1',
            'CA': '+1',
            'GB': '+44',
            'KE': '+254',
        }; 

        document.getElementById('locationLoader').classList.add('d-none');

        if (supportedCountries[locationData.country]) {
            document.getElementById('countryCode').innerText = supportedCountries[locationData.country]; 
            document.getElementById('registrationForm').classList.remove('d-none'); 
        } else {
            document.getElementById('locationError').classList.remove('d-none'); 
            document.getElementById('registrationForm').classList.add('d-none'); 
        }
    } else {
        document.getElementById('locationError').innerText = "Unable to determine your location.";
        document.getElementById('locationError').classList.remove('d-none'); 
        document.getElementById('registrationForm').classList.add('d-none'); 
        document.getElementById('locationLoader').classList.add('d-none');
    }
}

window.onload = init;
