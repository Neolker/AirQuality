# AirQuality

BACK-OFFICE projektu:

URL:
https://air-quality-b.tes-t.cz/administrace/

Volání backendu z frontendu:
## API endpoints

- **POST** požadavky předávají parametry v JSON formátu v **Body**, většinou jde o data z formulářů
- **GET** požadavky předávají parametry, které jsou součástí URL (route)

| URI                                                      | METODA | VSTUP                                                                                                     | VÝSTUP                                                          | POPIS |
| -------------------------------------------------------- | ------ | --------------------------------------------------------------------------------------------------------- | --------------------------------------------------------------- |
| **USER**                                                                                                                                                                                                                                        |
| https://air-quality-b.tes-t.cz/api/user/login/           | POST   | {`login`, `password`}                                                                                     | {`session_id`} - v cookies, {`status`, `error`, {user_data} }   | Přihlášení uživatele |
| https://air-quality-b.tes-t.cz/api/user/logout/          | GET    | null                                                                                                      | {`status`, `error`}                                             | Odhlášení uživatele |
| https://air-quality-b.tes-t.cz/api/user/get-logged-user/ | GET    | null                                                                                                      | {`status`, `error`, {user_data} }                               | Získání dat přihlášeného uživatele |
| https://air-quality-b.tes-t.cz/api/user/update-settings/ | POST   | {`degree`, `name`, `surname`, `company`, `email`, `phone`}                                                | {`status`, `error`, {user_data} }                               | Aktualizace uživatelských údajů |
| https://air-quality-b.tes-t.cz/api/user/update-password/ | POST   | {`new_password`, `new_password_again`}                                                                    | {`status`, `error`}                                             | Aktualizace uživatelského hesla |
| https://air-quality-b.tes-t.cz/api/user/registrate/      | POST   | {`login`, `degree`, `name`, `surname`, `company`, `email`, `phone`, `new_password`, `new_password_again`} | {`session_id`} - v cookies, {`status`, `error`, {user_data} }   | Registrace nového uživatele |
| https://air-quality-b.tes-t.cz/api/user/forgot-password/ | POST   | {`login`, `email`}                                                                                        | {`status`, `error`}                                             | Žádost o reset hesla uživatele |
| **DEVICE**                                                                                                                                                                                                                                      |
| https://air-quality-b.tes-t.cz/api/device/get/           | GET    | `device_id`                                                                                               | {`status`, `error`, {device_data} }                             | Získání 1 zařízení dle ID (pro přihlášeného uživatele) |
| https://air-quality-b.tes-t.cz/api/device/get-list/      | GET    | null                                                                                                      | {`status`, `error`, {device_datas[]} }                          | Získání všech zařízení (přihlášeného uživatele) |
| https://air-quality-b.tes-t.cz/api/device/add/           | POST   | {`serial_number`, `name`, `location`, `co2_green`, `co2_yellow`, `co2_red`}                               | {`status`, `error`, {device_data} }                             | Vytvoření zařízení (pro přihlášeného uživatele) |
| https://air-quality-b.tes-t.cz/api/device/update/        | POST   | {`device_id`, `serial_number`, `name`, `location`, `co2_green`, `co2_yellow`, `co2_red`}                  | {`status`, `error`, {device_data} }                             | |
| https://air-quality-b.tes-t.cz/api/device/delete/        | GET    | `device_id`                                                                                               | {`status`, `error`}                                             | |
| **DATA FROM SENSORS**                                                                                                                                                                                                                           | |
| https://air-quality-b.tes-t.cz/api/data/get-data/        | GET    |  `device_id`, `date`                                                                                      | {`status`, `error`, {sensors_datas}[] }                         | |
| https://air-quality-b.tes-t.cz/api/data/get-state/       | GET    |  `device_id`,                                                                                             | {`status`, `error`, `is_online` }                               | |
| https://air-quality-b.tes-t.cz/api/data/get-all-states/  | GET    |  null                                                                                                     | {`status`, `error`, {states}[] }                                | |




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
