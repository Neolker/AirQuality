import { LineChart } from "@mantine/charts";
import { Space, Title } from "@mantine/core";
import { useMemo } from "react";

const App = () => {
  const data = useMemo(() => {
    const dataPoints = [];
    let baseTime = new Date(); // Capture today's date
    baseTime.setHours(0, 0, 0, 0); // Reset to midnight

    for (let i = 0; i < 288; i++) {
      const time = new Date(baseTime.getTime() + i * 300000); // Calculate the time for this iteration
      dataPoints.push({
        date: time.toISOString().slice(11, 16), // HH:mm format
        CO2: Math.floor(400 + Math.random() * 100), // Random CO2 levels between 400 and 500 ppm
        VOC: Math.floor(100 + Math.random() * 50), // Random VOC levels between 100 and 150 ppb
        NOx: Math.floor(20 + Math.random() * 30), // Random NOx levels between 20 and 50 ppb
        Temperature: Math.floor(20 + Math.random() * 10), // Random Temperature between 20°C and 30°C
        Humidity: Math.floor(30 + Math.random() * 40), // Random Humidity between 30% and 70%
      });
    }

    return dataPoints;
  }, []);

  return (
    <>
      <Title order={2}>CO2, VOC and NOx</Title>
      <Space h="md" />

      <LineChart
        h={300}
        data={data}
        dataKey="date"
        series={[
          { name: "CO2", color: "blue.6" },
          { name: "VOC", color: "green.6" },
          { name: "NOx", color: "red.6" },
        ]}
        curveType="monotone"
        withLegend
        legendProps={{ verticalAlign: "bottom", height: 50 }}
        dotProps={{ r:0 }}
      />
      <Space h="md" />
      <Title order={2}>Tempreture and Humidity</Title>
      <Space h="md" />

      <LineChart
        h={300}
        data={data}
        dataKey="date"
        series={[
          { name: "Temperature", color: "orange.6" },
          { name: "Humidity", color: "blue.6" },
        ]}
        curveType="monotone"
        withLegend
        legendProps={{ verticalAlign: "bottom", height: 50 }}
        dotProps={{ r:0 }}

      />
    </>
  );
};

export default App;
