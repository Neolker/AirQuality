# AirQuality

Volání backendu pro aktualizaci dat:

URL: 
https://air-quality-b.tes-t.cz/data-update/

Metoda: 
POST

POST data: 
{
    "serial": "364a-9569-d5b5-3ab3-044b",
    "data": [
        {
            "unix-time": 1712505476, //double int - unix time stamp
            "co2-avg": 800.0, //float
            "co2-trend": 1, //rise
            "co2-unit": "ppm",
            "temp-avg": 23.45, //float
            "temp-trend": 0, //const
            "temp-unit": "°C",
            "humi-avg": 50.12, //float
            "humi-trend": -1, //fall
            "humi-unit": "%RH",
            "batt-avg": 3.25, //float
            "batt-unit": "V",
            "position": 1 // 0=left, 1=right, 2=bottom, 3=top, 4=front, 5=back
        },
        {
            "unix-time": 1712505478, //double int
            "co2-avg": 800.0, //float
            "co2-trend": 1, //rise
            "co2-unit": "ppm",
            "temp-avg": 23.45, //float
            "temp-trend": 0, //const
            "temp-unit": "°C",
            "humi-avg": 50.12, //float
            "humi-trend": -1, //fall
            "humi-unit": "%RH",
            "batt-avg": 3.25, //float
            "batt-unit": "V",
            "position": 3 // 0=left, 1=right, 2=bottom, 3=top, 4=front, 5=back
        }
    ]
}

Return:
{
    "serial": "364a-9569-d5b5-3ab3-044b",
    "data": [
        {
            "unix-time": 1712505476, //double int - unix time stamp
            "status": "OK" //Smazat z Gateway DB
        },
        {
            "unix-time": 1712505478, //double int
            "status": "KO" //Poslat přístě z Gateway FB znova
        }
    ],
    "co2-setting": { //Nastaveni semaforu pro indikaci úrovně CO2
        "red": 1500,
        "yellow": 1000,
        "green": 0 //spodni limit pro zelenou LED
    }
}
