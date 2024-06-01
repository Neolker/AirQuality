import { Text, Progress, Card } from '@mantine/core';

export function ProgressCard() {
  return (
    <Card withBorder radius="md" padding="xl" bg="var(--mantine-color-body)">
      <Text fz="xs" tt="uppercase" fw={700} c="dimmed">
        Devices online
      </Text>
      <Text fz="lg" fw={500}>
        3/5
      </Text>
      <Progress value={60} mt="md" size="lg" radius="xl" />
    </Card>
  );
}