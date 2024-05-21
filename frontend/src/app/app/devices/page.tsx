"use client";

import { DeviceCard } from "@/components/app/DeviceCard";
import { Badge, Button, Grid, Group, Space, Title, Text } from "@mantine/core";
import { useDevices } from "@/components/Contexts/DeviceContext";

const devices = [
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

const Devices = () => {
  // const { devices } = useDevices();
  const onlineDevices = devices.filter((device) => device.status === "Online");

  return (
    <>
      <Group justify="space-between">
        <Group>
          <Title>Devices</Title>
          <Badge color="green" size="lg">
            {onlineDevices.length} online
          </Badge>
          <Badge color="red" size="lg">
            {devices.length - onlineDevices.length} offline
          </Badge>
        </Group>
        <Button>Add device</Button>
      </Group>
      <Space h="lg" />
      <Grid gutter={{ base: 5, xs: "md", md: "xl", xl: "xl" }}>
        {devices.map((device) => (
          <Grid.Col span={{ base: 12, md: 6, lg: 4 }} key={device.id}>
            <DeviceCard
              id={device.id}
              serial_number={device.serial_number}
              name={device.name}
              status={device.status}
              location={device.location}
              co2_green={device.co2_green}
              co2_yellow={device.co2_yellow}
              co2_red={device.co2_red}
            />
          </Grid.Col>
        ))}
        {devices.length === 0 && (
          <Grid.Col>
            <Text>No devices found</Text>
          </Grid.Col>
        )}
      </Grid>
    </>
  );
};

export default Devices;
