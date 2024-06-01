#include <application.h>

#define BATTERY_UPDATE_INTERVAL (2 * 60 * 1000) // 1 minute

#define CO2_PUB_NO_CHANGE_INTERVAL (60 * 1000) // 1 minute
#define CO2_PUB_VALUE_CHANGE 50.0f             // 50 ppm

#define CO2_UPDATE_NORMAL_INTERVAL (1 * 60 * 1000) // 1 minute

#define CALIBRATION_DELAY (4 * 60 * 1000)    // 4 minutes
#define CALIBRATION_INTERVAL (1 * 60 * 1000) // 1 minute

#define SERIAL_NUMBER "484a-d5c5-9069-3ab9-0011"

// Semapfore LED GPIO assign
#define LED_RED TWR_GPIO_P12
#define LED_YLW TWR_GPIO_P13
#define LED_GRN TWR_GPIO_P14

// Semapfore
#define OFF 0
#define RED 1
#define YELLOW 2
#define GREEN 3

// LED instance
twr_led_t led;

// Button instance
twr_button_t button;

// CO2
event_param_t co2_event_param = {.next_pub = 0};

twr_scheduler_task_id_t calibration_task_id = 0;
int calibration_counter;

void calibration_task(void *param);

void calibration_start()
{
    calibration_counter = 32;

    twr_led_set_mode(&led, TWR_LED_MODE_BLINK_FAST);
    calibration_task_id = twr_scheduler_register(calibration_task, NULL, twr_tick_get() + CALIBRATION_DELAY);
    twr_radio_pub_string("co2-meter/-/calibration", "start");
}

void calibration_stop()
{
    twr_led_set_mode(&led, TWR_LED_MODE_OFF);
    twr_scheduler_unregister(calibration_task_id);
    calibration_task_id = 0;

    twr_module_co2_set_update_interval(CO2_UPDATE_NORMAL_INTERVAL);
    twr_radio_pub_string("co2-meter/-/calibration", "end");
}

void calibration_task(void *param)
{
    (void)param;

    twr_led_set_mode(&led, TWR_LED_MODE_BLINK_SLOW);

    twr_radio_pub_int("co2-meter/-/calibration", &calibration_counter);

    twr_module_co2_set_update_interval(CO2_UPDATE_NORMAL_INTERVAL);
    twr_module_co2_calibration(TWR_LP8_CALIBRATION_BACKGROUND_FILTERED);

    calibration_counter--;

    if (calibration_counter == 0)
    {
        calibration_stop();
    }

    twr_scheduler_plan_current_relative(CALIBRATION_INTERVAL);
}

void semaphore(color)
{
    if (color < 0 || color > 3)
    {
        color = 0;
    }

    switch (color)
    {
    case 0: // off
        twr_gpio_set_output(LED_RED, 0);
        twr_gpio_set_output(LED_YLW, 0);
        twr_gpio_set_output(LED_GRN, 0);
        twr_radio_pub_string("semapfore/-/led", "off");
        break;

    case 1: // red
        twr_gpio_set_output(LED_RED, 1);
        twr_gpio_set_output(LED_YLW, 0);
        twr_gpio_set_output(LED_GRN, 0);
        twr_radio_pub_string("semapfore/-/led", "red");
        break;

    case 2: // yellow
        twr_gpio_set_output(LED_RED, 0);
        twr_gpio_set_output(LED_YLW, 1);
        twr_gpio_set_output(LED_GRN, 0);
        twr_radio_pub_string("semapfore/-/led", "yellow");
        break;

    case 3: // red
        twr_gpio_set_output(LED_RED, 0);
        twr_gpio_set_output(LED_YLW, 0);
        twr_gpio_set_output(LED_GRN, 1);
        twr_radio_pub_string("semapfore/-/led", "green");
    }
}

void button_event_handler(twr_button_t *self, twr_button_event_t event, void *event_param)
{
    (void)self;
    (void)event_param;

    if (event == TWR_BUTTON_EVENT_CLICK)
    {
        static uint16_t button_count = 0;

        twr_led_pulse(&led, 100);

        twr_radio_pub_push_button(&button_count);
        button_count++;
    }
    else if (event == TWR_BUTTON_EVENT_HOLD)
    {
        if (!calibration_task_id)
        {
            calibration_start();
        }
        else
        {
            calibration_stop();
        }
    }
}

void battery_event_handler(twr_module_battery_event_t event, void *event_param)
{
    (void)event_param;

    float voltage;

    if (event == TWR_MODULE_BATTERY_EVENT_UPDATE)
    {
        if (twr_module_battery_get_voltage(&voltage))
        {
            twr_radio_pub_battery(&voltage);
        }
    }
}

void co2_event_handler(twr_module_co2_event_t event, void *event_param)
{
    event_param_t *param = (event_param_t *)event_param;
    float value;

    if (event == TWR_MODULE_CO2_EVENT_ERROR)
    {
        twr_lp8_error_t error;
        twr_module_co2_get_error(&error);
        int error_int = (int)error;

        twr_radio_pub_int("co2-meter/-/error", &error_int);
    }

    if (event == TWR_MODULE_CO2_EVENT_UPDATE)
    {
        if (twr_module_co2_get_concentration_ppm(&value))
        {
            if ((fabsf(value - param->value) >= CO2_PUB_VALUE_CHANGE) || (param->next_pub < twr_scheduler_get_spin_tick()) || calibration_task_id)
            {
                twr_radio_pub_co2(&value);

                if (value < 500.0f)
                {
                    semaphore(GREEN);
                }
                else if (value < 1000.0f)
                {
                    semaphore(YELLOW);
                }
                else if (value >= 1000.0f)
                {
                    semaphore(RED);
                }

                param->value = value;
                param->next_pub = twr_scheduler_get_spin_tick() + CO2_PUB_NO_CHANGE_INTERVAL;
            }
        }
    }
}

void application_init(void)
{
    // Initialize LED on board
    twr_led_init(&led, TWR_GPIO_LED, false, false);
    twr_led_set_mode(&led, TWR_LED_MODE_OFF);

    // Initialize radio
    twr_radio_init(TWR_RADIO_MODE_NODE_SLEEPING);

    // Initialize red LED for Semaphore
    twr_gpio_init(LED_RED);
    twr_gpio_set_mode(LED_RED, TWR_GPIO_MODE_OUTPUT);
    twr_gpio_set_output(LED_RED, 0);

    // Initialize yellow LED for Semaphore
    twr_gpio_init(LED_YLW);
    twr_gpio_set_mode(LED_YLW, TWR_GPIO_MODE_OUTPUT);
    twr_gpio_set_output(LED_YLW, 0);

    // Initialize green LED for Semaphore
    twr_gpio_init(LED_GRN);
    twr_gpio_set_mode(LED_GRN, TWR_GPIO_MODE_OUTPUT);
    twr_gpio_set_output(LED_GRN, 0);

    // Initialize button
    twr_button_init(&button, TWR_GPIO_BUTTON, TWR_GPIO_PULL_DOWN, false);
    twr_button_set_hold_time(&button, 10000);
    twr_button_set_event_handler(&button, button_event_handler, NULL);

    // Initialize battery
    twr_module_battery_init();
    twr_module_battery_set_event_handler(battery_event_handler, NULL);
    twr_module_battery_set_update_interval(BATTERY_UPDATE_INTERVAL);

    // Initialize CO2
    twr_module_co2_init();
    twr_module_co2_set_update_interval(CO2_UPDATE_NORMAL_INTERVAL);
    twr_module_co2_set_event_handler(co2_event_handler, &co2_event_param);

    twr_radio_pairing_request("AirQuality", FW_VERSION);

    twr_radio_pub_string("serial", SERIAL_NUMBER); // Send S/N

    twr_led_pulse(&led, 5000);
}
