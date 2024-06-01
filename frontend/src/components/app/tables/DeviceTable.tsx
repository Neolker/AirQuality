"use client"

import { Table, Progress, Anchor, Text, Group } from '@mantine/core';
import classes from "./DeviceTable.module.css"

const data = [
  {
    title: 'Foundation',
    author: 'Isaac Asimov',
    year: 1951,
    reviews: { positive: 2223, negative: 259 },
  },
  {
    title: 'Frankenstein',
    author: 'Mary Shelley',
    year: 1818,
    reviews: { positive: 5677, negative: 1265 },
  },
  {
    title: 'Solaris',
    author: 'Stanislaw Lem',
    year: 1961,
    reviews: { positive: 3487, negative: 1845 },
  },
  {
    title: 'Dune',
    author: 'Frank Herbert',
    year: 1965,
    reviews: { positive: 8576, negative: 663 },
  },
  {
    title: 'The Left Hand of Darkness',
    author: 'Ursula K. Le Guin',
    year: 1969,
    reviews: { positive: 6631, negative: 993 },
  },
  {
    title: 'A Scanner Darkly',
    author: 'Philip K Dick',
    year: 1977,
    reviews: { positive: 8124, negative: 1847 },
  },
];

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
      air_quality: 30
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
      air_quality: 30
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
      air_quality: 30
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
      air_quality: 30
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
      air_quality: 30
    },
  ];

export function DeviceTable() {
  const rows = devices_mock.map((row) => {
    const qualityIndex = row.air_quality
    // const positiveReviews = (row.reviews.positive / totalReviews) * 100;
    // const negativeReviews = (row.reviews.negative / totalReviews) * 100;

    return (
      <Table.Tr key={row.name}>
        <Table.Td>
          <Anchor component="button" fz="sm">
            {row.name}
          </Anchor>
        </Table.Td>
        <Table.Td>{row.serial_number}</Table.Td>
        <Table.Td>
          <Anchor component="button" fz="sm">
            {row.location}
          </Anchor>
        </Table.Td>
        <Table.Td>{Intl.NumberFormat().format(qualityIndex)}</Table.Td>
        <Table.Td>
          <Group justify="space-between">
            <Text fz="xs" c="teal" fw={700}>
              {qualityIndex.toFixed(0)}%
            </Text>
            <Text fz="xs" c="red" fw={700}>
              {qualityIndex.toFixed(0)}%
            </Text>
          </Group>
          <Progress.Root>
            <Progress.Section
              className={classes.progressSection}
              value={qualityIndex}
              color="teal"
            />

            <Progress.Section
              className={classes.progressSection}
              value={qualityIndex}
              color="red"
            />
          </Progress.Root>
        </Table.Td>
      </Table.Tr>
    );
  });

  return (
    <Table.ScrollContainer minWidth={800}>
      <Table verticalSpacing="xs">
        <Table.Thead>
          <Table.Tr>
            <Table.Th>Device name</Table.Th>
            <Table.Th>Serial number</Table.Th>
            <Table.Th>Location</Table.Th>
            <Table.Th>Air quality index</Table.Th>
            <Table.Th>Air quality index</Table.Th>
          </Table.Tr>
        </Table.Thead>
        <Table.Tbody>{rows}</Table.Tbody>
      </Table>
    </Table.ScrollContainer>
  );
}