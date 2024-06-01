#include <application.h>

#define BATTERY_UPDATE_INTERVAL (10 * 1000) // 1 minute

#define TEMPERATURE_PUB_INTERVAL (10 * 1000)
#define TEMPERATURE_PUB_DIFFERENCE 1.0f

#define CO2_PUB_NO_CHANGE_INTERVAL (60 * 1000) // 1 minute
#define CO2_PUB_VALUE_CHANGE 50.0f             // 50 ppm

#define CO2_UPDATE_NORMAL_INTERVAL (1 * 60 * 1000) // 1 minute

#define CALIBRATION_DELAY (4 * 60 * 1000)    // 4 minutes
#define CALIBRATION_INTERVAL (1 * 60 * 1000) // 1 minute

#define ACCELEROMETER_UPDATE_NORMAL_INTERVAL (1 * 1000)

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

// Thermometer instance
twr_tmp112_t tmp112;

// Accelerometer instance
twr_lis2dh12_t lis2dh12;

// Dice instance
twr_dice_t dice;

// Time of next temperature report
twr_tick_t tick_temperature_report = 0;

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

void semaphore(int color)
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
        break;

    default:
        twr_gpio_set_output(LED_RED, 0);
        twr_gpio_set_output(LED_YLW, 0);
        twr_gpio_set_output(LED_GRN, 0);
        twr_radio_pub_string("semapfore/-/led", "off");
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
            twr_radio_pub_string("serial", SERIAL_NUMBER); // Send S/N right after battery voltage
        }
    }
}

// This function dispatches thermometer events
void tmp112_event_handler(twr_tmp112_t *self, twr_tmp112_event_t event, void *event_param)
{
    // Update event?
    if (event == TWR_TMP112_EVENT_UPDATE)
    {
        float temperature;

        // Successfully read temperature?
        if (twr_tmp112_get_temperature_celsius(self, &temperature))
        {
            twr_log_info("APP: Temperature = %0.1f C", temperature);

            // Implicitly do not publish message on radio
            bool publish = false;

            // Is time up to report temperature?
            if (twr_tick_get() >= tick_temperature_report)
            {
                // Publish message on radio
                publish = true;
            }

            // Last temperature value used for change comparison
            static float last_published_temperature = NAN;

            // Temperature ever published?
            if (last_published_temperature != NAN)
            {
                // Is temperature difference from last published value significant?
                if (fabsf(temperature - last_published_temperature) >= TEMPERATURE_PUB_DIFFERENCE)
                {
                    twr_log_info("APP: Temperature change threshold reached");

                    // Publish message on radio
                    publish = true;
                }
            }

            // Publish message on radio?
            if (publish)
            {
                twr_log_info("APP: Publish temperature");

                // Publish temperature message on radio
                twr_radio_pub_temperature(TWR_RADIO_PUB_CHANNEL_R1_I2C0_ADDRESS_ALTERNATE, &temperature);

                // Schedule next temperature report
                tick_temperature_report = twr_tick_get() + TEMPERATURE_PUB_INTERVAL;

                // Remember last published value
                last_published_temperature = temperature;
            }
        }
    }
    // Error event?
    else if (event == TWR_TMP112_EVENT_ERROR)
    {
        twr_log_error("APP: Thermometer error");
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

// This function dispatches accelerometer events
void lis2dh12_event_handler(twr_lis2dh12_t *self, twr_lis2dh12_event_t event, void *event_param)
{
    // Update event?
    if (event == TWR_LIS2DH12_EVENT_UPDATE)
    {
        twr_lis2dh12_result_g_t result;

        // Successfully read accelerometer vectors?
        if (twr_lis2dh12_get_result_g(self, &result))
        {
            twr_log_info("APP: Acceleration = [%.2f,%.2f,%.2f]", result.x_axis, result.y_axis, result.z_axis);

            // Update dice with new vectors
            twr_dice_feed_vectors(&dice, result.x_axis, result.y_axis, result.z_axis);

            // This variable holds last dice face
            static twr_dice_face_t last_face = TWR_DICE_FACE_UNKNOWN;

            // Get current dice face
            twr_dice_face_t face = twr_dice_get_face(&dice);

            // Did dice face change from last time?
            if (last_face != face)
            {
                // Remember last dice face
                last_face = face;

                // Convert dice face to integer
                int orientation = face;

                twr_log_info("APP: Publish orientation = %d", orientation);

                // Publish orientation message on radio
                // Be careful, this topic is only development state, can be change in future.
                twr_radio_pub_int("orientation", &orientation);
            }
        }
    }
    // Error event?
    else if (event == TWR_LIS2DH12_EVENT_ERROR)
    {
        twr_log_error("APP: Accelerometer error");
    }
}

void application_init(void)
{

    // Initialize log
    twr_log_init(TWR_LOG_LEVEL_INFO, TWR_LOG_TIMESTAMP_ABS);
    twr_log_info("APP: Reset");

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

    // Initialize thermometer
    twr_tmp112_init(&tmp112, TWR_I2C_I2C0, 0x49);
    twr_tmp112_set_event_handler(&tmp112, tmp112_event_handler, NULL);
    twr_tmp112_set_update_interval(&tmp112, TEMPERATURE_PUB_INTERVAL);

    // Initialize CO2
    twr_module_co2_init();
    twr_module_co2_set_update_interval(CO2_UPDATE_NORMAL_INTERVAL);
    twr_module_co2_set_event_handler(co2_event_handler, &co2_event_param);

    // Initialize accelerometer
    twr_lis2dh12_init(&lis2dh12, TWR_I2C_I2C0, 0x19);
    twr_lis2dh12_set_event_handler(&lis2dh12, lis2dh12_event_handler, NULL);
    twr_lis2dh12_set_update_interval(&lis2dh12, ACCELEROMETER_UPDATE_NORMAL_INTERVAL);

    // Initialize dice
    twr_dice_init(&dice, TWR_DICE_FACE_UNKNOWN);

    twr_radio_pairing_request("AirQuality", FW_VERSION);

    twr_led_pulse(&led, 5000);
}
