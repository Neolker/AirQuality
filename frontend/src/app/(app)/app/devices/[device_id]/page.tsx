"use client";

import { useDevices } from "@/components/Contexts/DeviceContext";
import DeviceData from "@/components/app/DeviceData";
import { LineChart } from "@mantine/charts";
import {
  Badge,
  Button,
  Center,
  Divider,
  Group,
  Loader,
  rem,
  Space,
  Title,
} from "@mantine/core";
import { DatePickerInput } from "@mantine/dates";
import { IconArrowBackUp, IconCalendar } from "@tabler/icons-react";
import { useRouter } from "next/navigation";
import { useEffect, useMemo, useState } from "react";

interface SensorData {
  date: string;
  CO2: number;
  VOC: number;
  NOx: number;
  Temperature: number;
  Humidity: number;
}

function calculateIndividualAQI(
  concentration: number,
  lowConcentration: number,
  highConcentration: number,
  lowIndex: number,
  highIndex: number
) {
  return (
    ((highIndex - lowIndex) / (highConcentration - lowConcentration)) *
      (concentration - lowConcentration) +
    lowIndex
  );
}

function calculateAQI(data: SensorData[]) {
  return data.map((dataPoint) => {
    const co2Aqi = calculateIndividualAQI(dataPoint.CO2, 400, 500, 0, 50);
    const vocAqi = calculateIndividualAQI(dataPoint.VOC, 100, 150, 0, 50);
    const noxAqi = calculateIndividualAQI(dataPoint.NOx, 20, 50, 0, 50);

    // Simplified AQI calculation: taking the max of the calculated AQIs
    const maxAqi = Math.max(co2Aqi);

    return { ...dataPoint, AQI: Math.round(maxAqi) };
  });
}

const formatDateToDDMMYYYY = (date: string | number | Date) => {
  const d = new Date(date);
  const day = String(d.getDate()).padStart(2, "0");
  const month = String(d.getMonth() + 1).padStart(2, "0");
  const year = d.getFullYear();
  return `${day}.${month}.${year}`;
};

function convertSecondsToMinutesAndSeconds(seconds: number) {
  const minutes = Math.floor(seconds / 60);
  const remainingSeconds = seconds % 60;
  return `${minutes}m ${remainingSeconds}s`;
}

const DevicePage = ({ params }: { params: { device_id: string } }) => {
  const { getSensorData, devices } = useDevices();
  const [data, setData] = useState<SensorData[]>([]);
  const [loading, setLoading] = useState(true);
  const [selectedDate, setSelectedDate] = useState<Date | null>(new Date());
  const device = devices.filter(
    (device) => device.device_id === params.device_id.toString()
  )[0];
  const router = useRouter();
  const refreshSensorDataInterval =
    parseInt(process.env.NEXT_PUBLIC_REFRESH_SENSOR_DATA_INTERVAL ?? "") || 600;
  const [remainingTimeToRefresh, setRemainingTimeToRefresh] = useState(
    refreshSensorDataInterval
  );

  const fetchData = async (date: Date) => {
    setLoading(true);
    const formattedDate = formatDateToDDMMYYYY(date); // Format date to DD.MM.YYYY
    const sensorData = await getSensorData(params.device_id, formattedDate);
    const formattedData = Object.values(sensorData).map((item: any) => {
      const date = new Date(item.unix_time * 1000);
      date.setHours(date.getHours() + 2); // Convert to UTC +2 hours
      return {
        date: date.toISOString().slice(11, 16), // Convert to HH:mm format
        CO2: Math.round(parseFloat(item.co2_avg) * 10) / 10,
        VOC: Math.round(parseFloat(item.voc_avg) * 10) / 10,
        NOx: Math.round(parseFloat(item.nox_avg) * 10) / 10,
        Temperature: Math.round(parseFloat(item.temp_avg) * 10) / 10,
        Humidity: Math.round(parseFloat(item.humi_avg) * 10) / 10,
        Temp_trend: parseFloat(item.temp_trend),
        Humi_trend: parseFloat(item.humi_trend),
        CO2_trend: parseFloat(item.co2_trend),
        Battery:
          Math.round(
            ((parseFloat(item.batt_avg) - 1.5) / (6.5 - 1.5)) * 100 * 10
          ) / 10,
        Position: item.position,
      };
    });
    setData(formattedData);
    setLoading(false);
  };

  useEffect(() => {
    if (selectedDate) {
      fetchData(selectedDate);

      const interval = setInterval(() => {
        fetchData(selectedDate);
      }, refreshSensorDataInterval * 1000);

      const timerInterval = setInterval(() => {
        setRemainingTimeToRefresh((prev) => (prev > 0 ? prev - 1 : 300));
      }, 1000);

      return () => {
        clearInterval(interval);
        clearInterval(timerInterval);
      };
    }
  }, [params.device_id, selectedDate]);

  const dataWithAQI = useMemo(() => calculateAQI(data), [data]);

  if (loading) {
    return (
      <Center>
        <Loader />
      </Center>
    );
  }
  const icon = (
    <IconCalendar style={{ width: rem(18), height: rem(18) }} stroke={1.5} />
  );
  const lastValue = (key: keyof SensorData) =>
    data.length > 0 ? data[data.length - 1][key] : "N/A";

  return (
    <>
      <Button
        variant="outline"
        onClick={() => {
          router.push("/app/devices");
        }}
      >
        <IconArrowBackUp /> Back to devices
      </Button>
      <Divider my="lg" label="Current device data" />

      <DeviceData lastValue={lastValue} device={device} />

      <Divider my="lg" label="Charts" />

      <Group justify="space-between">
        <Group>
          <Badge variant="transparent" color="gray" size="lg">
            Next Refresh in:{" "}
            {convertSecondsToMinutesAndSeconds(remainingTimeToRefresh)}
          </Badge>
        </Group>

        <DatePickerInput
          leftSection={icon}
          leftSectionPointerEvents="none"
          label="Pick date"
          placeholder="Pick date"
          value={selectedDate}
          onChange={setSelectedDate}
        />
      </Group>
      <Space h="lg" />
      <Title order={2}>CO2</Title>
      <Space h="md" />
      <LineChart
        h={300}
        data={data}
        dataKey="date"
        series={[{ name: "CO2", color: "blue.6" }]}
        curveType="monotone"
        withLegend
        unit="ppm"
        legendProps={{ verticalAlign: "bottom", height: 50 }}
        dotProps={{ r: 0 }}
        lineChartProps={{ syncId: "air" }}
        referenceLines={[
          {
            y: device?.co2_green,
            label: "Good (less is better)",
            color: "green.6",
          },
          { y: device?.co2_yellow, label: "Moderate ", color: "yellow.6" },
          { y: device?.co2_red, label: "Unhealthy ", color: "red.6" },
        ]}
      />
      <Space h="md" />
      <Title order={2}>Temperature</Title>
      <Space h="md" />
      <LineChart
        h={300}
        data={data}
        dataKey="date"
        unit="°C"
        series={[{ name: "Temperature", color: "orange.6" }]}
        curveType="monotone"
        withLegend
        legendProps={{ verticalAlign: "bottom", height: 50 }}
        dotProps={{ r: 0 }}
        lineChartProps={{ syncId: "air" }}
        referenceLines={[
          { y: 0, label: "0°C", color: "blue.6" },
          { y: 15, label: "15°C", color: "cyan.6" },
          { y: 21, label: "21°C", color: "green.6" },
          { y: 30, label: "30°C", color: "orange.6" },
          { y: 45, label: "45°C", color: "red.6" },
        ]}
      />

      <Space h="md" />
      <Title order={2}>Battery</Title>
      <Space h="md" />
      <LineChart
        h={300}
        data={data}
        dataKey="date"
        series={[{ name: "Battery", color: "green.6" }]}
        curveType="monotone"
        unit="%"
        withLegend
        legendProps={{ verticalAlign: "bottom", height: 50 }}
        dotProps={{ r: 0 }}
        lineChartProps={{ syncId: "air" }}
        referenceLines={[
          { y: 15, label: "15%", color: "red.6" },
          { y: 30, label: "30%", color: "yellow.6" },
        ]}
      />

      {/* <Space h="md" />
      <Title order={2}>Humidity</Title>
      <Space h="md" />
      <LineChart
        h={300}
        data={data}
        dataKey="date"
        unit="%"
        series={[{ name: "Humidity", color: "blue.6" }]}
        curveType="monotone"
        withLegend
        legendProps={{ verticalAlign: "bottom", height: 50 }}
        dotProps={{ r: 0 }}
        lineChartProps={{ syncId: "air" }}
        referenceLines={[
          { y: 0, label: "0%", color: "red.6" },
          { y: 30, label: "30% or more ↑", color: "green.6" },
          { y: 50, label: "50% or less ↓", color: "green.6" },
          { y: 80, label: "70% or less ↓", color: "yellow.6" },
          { y: 100, label: "100% or less ↓", color: "red.6" },
        ]}
      /> */}
      {/* <Space h="lg" />
      <Title order={2}>VOC)</Title>
      <Space h="md" />
      <LineChart
        h={300}
        data={data}
        dataKey="date"
        series={[{ name: "VOC", color: "green.6" }]}
        curveType="monotone"
        withLegend
        legendProps={{ verticalAlign: "bottom", height: 50 }}
        dotProps={{ r: 0 }}
        lineChartProps={{ syncId: "air" }}
      />
      <Space h="md" />
      <Title order={2}>NOx)</Title>
      <Space h="md" />
      <LineChart
        h={300}
        data={data}
        dataKey="date"
        series={[{ name: "NOx", color: "red.6" }]}
        curveType="monotone"
        withLegend
        legendProps={{ verticalAlign: "bottom", height: 50 }}
        dotProps={{ r: 0 }}
        lineChartProps={{ syncId: "air" }}
      /> */}
    </>
  );
};

export default DevicePage;
