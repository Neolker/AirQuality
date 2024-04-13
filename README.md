# AirQuality

Volání backendu pro aktualizaci dat:

URL: 
https://air-quality-b.tes-t.cz/data-update/

Metoda: 
POST

POST data: 
{

    "serial":"364a-9569-d5b5-3ab3-044b",
    "data":[
        {
            "unix-time":1712505476,
            "co2-avg":800,
            "co2-trend":1,
            "co2-unit":"ppm",
            "temp-avg":23.45,
            "temp-trend":0,
            "temp-unit":"°C",
            "humi-avg":50.12,
            "humi-trend":-1,
            "humi-unit":"%RH",
            "batt-avg":3.25,
            "batt-unit":"V",
            "position":1
        },
        {
            "unix-time":1712505478,
            "co2-avg":800,
            "co2-trend":1,
            "co2-unit":"ppm",
            "temp-avg":23.45,
            "temp-trend":0,
            "temp-unit":"°C",
            "humi-avg":50.12,
            "humi-trend":-1,
            "humi-unit":"%RH",
            "batt-avg":3.25,
            "batt-unit":"V",
            "position":3
        }
    ]

}

Return:
{

    "serial":"364a-9569-d5b5-3ab3-044b",
    "data":[
        {
            "unix-time":1712505476,
            "status":"OK",
            "type":"already-exists"
        },
        {
            "unix-time":1712505478,
            "status":"OK",
            "type":"already-exists"
        }
    ],
    "co2-setting":{
        "red":1499,
        "yellow":999,
        "green":99
    }

}
