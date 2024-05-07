# AirQuality

BACK-OFFICE projektu:

URL:
https://air-quality-b.tes-t.cz/administrace/

Volání backendu z frontendu:
## API endpoints

- **POST** požadavky předávají parametry v JSON formátu v **Body**, většinou jde o data z formulářů
- **GET** požadavky předávají parametry, které jsou součástí URL (route)

| URI                                                      | METODA | VSTUP                                                                                                     | VÝSTUP                                                          |
| -------------------------------------------------------- | ------ | --------------------------------------------------------------------------------------------------------- | --------------------------------------------------------------- |
| **USER**                                                                                                                                                                                                                                        |
| https://air-quality-b.tes-t.cz/api/user/login/           | POST   | {`login`, `password`}                                                                                     | {`session_id`} - v cookies, {`status`, `error`, {userdata} }    |
| https://air-quality-b.tes-t.cz/api/user/logout/          | GET    | null                                                                                                      | {`status`, `error`}                                             |
| https://air-quality-b.tes-t.cz/api/user/update-settings/ | POST   | {`degree`, `name`, `surname`, `company`, `email`, `phone`}                                                | {`status`, `error`, {userdata} }                                |
| https://air-quality-b.tes-t.cz/api/user/update-password/ | POST   | {`new_password`, `new_password_again`}                                                                    | {`status`, `error`}                                             |
| https://air-quality-b.tes-t.cz/api/user/registrate/      | POST   | {`login`, `degree`, `name`, `surname`, `company`, `email`, `phone`, `new_password`, `new_password_again`} | {`session_id`} - v cookies, {`status`, `error`, {userdata} }    |
| https://air-quality-b.tes-t.cz/api/user/forgot-password/ | POST   | {`login`, `email`}                                                                                        | {`status`, `error`}                                             |
| **DEVICE**                        |
| device/get                        | GET    | `session_id`, `user_id`, `device_id`                                | `device_id`, `name`, `location`, `status`                                                                                                                                                                                                                                                                                               |
| device/update                     | POST   | `session_id`, `user_id`, `device_id`                                | `device_id`, `name`, `location`, `status`                                                                                                                                                                                                                                                                                               |
| device/get-data                   | GET    | `session_id`, `user_id`, `device_id`, `date`                        | `data[] {` `device_id`, `date`, `AQI[] {timestamp, value}`, `CO2_data[] { timestamp, value }`, `VOC_data[] { timestamp, value }`, `NOX_data[] { timestamp, value }`, `temperature_data[] { timestamp, value }`,`humidity_data[] { timestamp, value }`,` battery_data[] { timestamp, value }`,`position_data[] { timestamp, value }` `}` |
| device/add                        | POST   | `session_id`, `user_id`, `name`, `location`                         | `device_id`, `name`, `location`, `status`                                                                                                                                                                                                                                                                                               |
| device/delete                     | POST   | `session_id`, `user_id`, `device_id`                                | `device_id`, `name`, `location`, `status`                                                                                                                                                                                                                                                                                               |


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

Vysvětlivka dat:

Trend: -1 = klesání, 0 = konstatní, 1 = nárust

Pozice: 0 = horní strana, 1 = spodní strana, 2 = levá strana, 3 = pravá strana, 4 = přední strana, 5 = zadní strana

Status: OK = v pořádku, KO = není v pořádku

Type: not-insert = záznam neuložen, already-exists = záznam již existuje a nový nebyl uložen, insert = záznam úspěšně uložen
