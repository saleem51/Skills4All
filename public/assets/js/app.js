// Url de l'api pour récupérer les informations concernant la météo
const urlApi = "https://api.open-meteo.com/v1/forecast?latitude=48.85&longitude=2.35&hourly=temperature_2m,relativehumidity_2m,apparent_temperature,rain,visibility,soil_temperature_54cm&current_weather=true";

//Fonction asynchrone pour la récupération des données
async function getWeather(url){
    const response = await fetch(url);

    return await response.json();
}

let now;

const weatherBlock = document.querySelector('#weather-block');
const degrees = document.querySelector('#degrees');

//Appel de la fonction
getWeather(urlApi).then(weather => {
        console.log(weather);
        degrees.innerHTML = `${weather.current_weather.temperature} ${weather.hourly_units.temperature_2m}`;
        let latitude = weather.latitude;
        let longitude = weather.longitude;


//Récupération du nom de la ville dynamiquement à partir des latitudes et longitudes via une autre Api
let reverseGeoCodingUrl = `https://api.geoapify.com/v1/geocode/reverse?lat=${latitude}&lon=${longitude}&apiKey=8b2abb52c9114f9d951053b840b52146`;
let requestOptions = {
    method: 'GET',
};

//Appel de la seconde API pour le nom de la ville
fetch(reverseGeoCodingUrl, requestOptions)
    .then(response => response.json())
    .then(result => {
             document.querySelector('#city').innerHTML = result.features[0].properties.city;
             weather.hourly.time.forEach( (time, index) => {
                  now = new Date();
                 now.setMinutes(0);
                 let formatTime = now.toISOString().replace(/.\d+Z$/g, "Z").slice(0, this.length - 4);

                 const hourTemperature = document.querySelector('#hourDegrees');
                   //Affichage de la température présente dans le tableau si les deux date sont identiques et si l'index de la temperature et l'index de l'heure sont identique pour avoir le changement de température toutes les heures
                 if(time === formatTime && weather.hourly.temperature_2m.indexOf(weather.hourly.temperature_2m[index]) === weather.hourly.time.indexOf(weather.hourly.time[index])){
                     hourTemperature.innerHTML = `${(parseInt(time.substring(11, 13)) + 2) }h : ${weather.hourly.temperature_2m[index]} ${weather.hourly_units.temperature_2m}`;
                 }
             });
             let heure = new Date(Date.now());
             document.querySelector('#hour').innerHTML = `${heure.getHours()}: ${heure.getMinutes()}: ${heure.getSeconds()}`;
        }
    )
    .catch(error => console.log('error', error));
});