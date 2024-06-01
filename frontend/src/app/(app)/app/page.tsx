"use client"

import { Badge, Button, Grid, Group, Space, Text, Title, Container, SimpleGrid, Skeleton, rem } from "@mantine/core";

import MapChart from "../../../components/app/MapChart";

const PRIMARY_COL_HEIGHT = rem(600);

const devices_mock = [
  {
    device_id: 1,
    serial_number: "SN-001",
    name: "Device 1",
    status: "Online",
    location: "Location 1",
    co2_green: 400,
    co2_yellow: 800,
    co2_red: 1200,
  },
  {
    device_id: 2,
    serial_number: "SN-002",
    name: "Device 2",
    status: "Offline",
    location: "Location 2",
    co2_green: 400,
    co2_yellow: 800,
    co2_red: 1200,
  },
  {
    device_id: 3,
    serial_number: "SN-003",
    name: "Device 3",
    status: "Online",
    location: "Location 3",
    co2_green: 400,
    co2_yellow: 800,
    co2_red: 1200,
  },
  {
    device_id: 4,
    serial_number: "SN-004",
    name: "Device 4",
    status: "Offline",
    location: "Location 4",
    co2_green: 400,
    co2_yellow: 800,
    co2_red: 1200,
  },
  {
    device_id: 5,
    serial_number: "SN-005",
    name: "Device 5",
    status: "Online",
    location: "Location 5",
    co2_green: 400,
    co2_yellow: 800,
    co2_red: 1200,
  },
];

const App = () => {

  const SECONDARY_COL_HEIGHT = `calc(${PRIMARY_COL_HEIGHT} / 2 - var(--mantine-spacing-md) / 2)`;

  const onlineDevices = devices_mock.filter((device) => device.status === "Online");

  return (
    <>
      <Group justify="space-between">
        <Group>
          <Title>Dashboard</Title>
          <Badge color="green" size="lg">
            {onlineDevices.length} Devices online
          </Badge>
          <Badge color="red" size="lg">
            {devices_mock.length - onlineDevices.length} Devices offline
          </Badge>
        </Group>
      </Group>
      <SimpleGrid cols={{ base: 1, sm: 2 }} spacing="md" my="md">
        <Skeleton height={PRIMARY_COL_HEIGHT} radius="md" animate={false} />
        <Grid gutter="md">
          <Grid.Col>
            <Skeleton height={SECONDARY_COL_HEIGHT} radius="md" animate={false} />
          </Grid.Col>
          <Grid.Col span={6}>
            <Skeleton height={SECONDARY_COL_HEIGHT} radius="md" animate={false} />
          </Grid.Col>
          <Grid.Col span={6}>
            <Skeleton height={SECONDARY_COL_HEIGHT} radius="md" animate={false} />
          </Grid.Col>
        </Grid>
      </SimpleGrid>
      <MapChart />
    </>
  );
};

export default App;
