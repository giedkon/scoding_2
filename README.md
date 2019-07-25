# scoding_2

OWM API naudojimas ir išvedimas vartotojui pagrinde \app\Http\Controllers\OWMApiController.php

Atnaujinimo bei įspėjimo kodas \app\Console\Commands\UpdateWeather.php ir paleidžiamas kas minute \app\Console\Kernel.php ir crontab.

Vieta pasirenkama naudojant google maps api žemėlapi, ir paspaudus Get Weather mygtuką paduodama užklausa į OpenWeatherMap.
Kiekvieną kartą vartotojas pasirenka skirtingą miestą, jis yra pridedamas į sąrašą, tuo pačiu vartotojas gali pridėti savo į įspėjamų el. paštų sąrašą.

![IMG1](https://i.imgur.com/9LitLKF.png)

Įspėjimą vartotojas gaus kai automatiškai bus iškviesta command'ą updateWeather (kuri turėtu pasileisti kas minute) ir vartotojas turėtu gauti el. paštą su įspėjimu, kad vėjas yra virš 10m/s arba nukrito žemiau negu 10m/s

![IMG2](https://i.imgur.com/Xn3IcYO.png)
