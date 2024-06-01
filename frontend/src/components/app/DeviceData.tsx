"use client";

import { Badge, Center, Grid, Group, Paper, Stack, Text } from "@mantine/core";
import { IconCpu2, IconMapPin } from "@tabler/icons-react";

export default function DeviceData({
  lastValue,
  device,
}: {
  lastValue: any;
  device: any;
}) {
  if (!device) {
    return null;
  }
  return (
    <Grid>
      <Grid.Col span={{ base: 12, xs: 12 }}>
        <Group justify="space-between" grow style={{ marginBottom: 5 }}>
          <Text w={500}>{device.name}</Text>
          <Group justify="flex-end">
            <Badge color={device.status === "Online" ? "green" : "red"}>
              {device.status}
            </Badge>
          </Group>
        </Group>
        <Stack>
          <Group>
            <IconCpu2 size={16} />
            <Text size="sm">SN: {device.serial_number}</Text>
          </Group>
          <Group>
            <IconMapPin size={16} />
            <Text size="sm">Location: {device.location}</Text>
          </Group>
        </Stack>
      </Grid.Col>
      <Grid.Col span={{ base: 12, xs: 4 }}>
        <Paper shadow="xs" p="lg" withBorder radius="md">
          <Center>
            <Stack>
              <Badge size="xl">CO2</Badge>
              <Text fw={700} size="xl" ta="center">
                {lastValue("CO2")} ppm
              </Text>
            </Stack>
          </Center>
        </Paper>
      </Grid.Col>
      <Grid.Col span={{ base: 12, xs: 4 }}>
        <Paper shadow="xs" p="lg" withBorder radius="md">
          <Center>
            <Stack>
              <Badge size="xl">Temperature</Badge>
              <Text fw={700} size="xl" ta="center">
                {lastValue("Temperature")} °C
              </Text>
            </Stack>
          </Center>
        </Paper>
      </Grid.Col>
      <Grid.Col span={{ base: 12, xs: 4 }}>
        <Paper shadow="xs" p="lg" withBorder radius="md">
          <Center>
            <Stack>
              <Badge size="xl">Humidity</Badge>
              <Text fw={700} size="xl" ta="center">
                {lastValue("Humidity")}%
              </Text>
            </Stack>
          </Center>
        </Paper>
      </Grid.Col>
    </Grid>
  );
}
