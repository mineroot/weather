Weather App
-----
[![CI](https://github.com/mineroot/weather/actions/workflows/ci.yml/badge.svg)](https://github.com/mineroot/weather/actions/workflows/ci.yml)

Requirements:
- Docker (tested on version 28.1 on linux)

Run:
- clone this repo `git clone https://github.com/mineroot/weather.git && cd weather`
- obtain API key https://www.weatherapi.com/ and set APP_WEATHER_API_KEY variable `echo "APP_WEATHER_API_KEY=paste_your_api_key" > .env.local`
- build image `docker compose build --no-cache`
- run services `docker compose up -d --wait`
- open https://localhost in browser and accept self-signed certificate

Optional steps:
- change ownership of `vendor` directory `sudo chown -R user:group vendor`
- run tests `docker compose exec php composer test`
- run static analysis `docker compose exec php composer analyze`
- watch logs `docker compose exec php tail -f /app/var/log/dev.log`
- shutdown services `docker compose down`
