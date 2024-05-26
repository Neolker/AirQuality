import { Modal, Button, TextInput, NumberInput, Group } from "@mantine/core";
import { useForm } from "@mantine/form";
import { useEffect } from "react";

interface DeviceModalFormProps {
  opened: boolean;
  onClose: () => void;
  onSubmit: (values: DeviceFormValues) => void;
  initialValues?: DeviceFormValues;
}

export interface DeviceFormValues {
  device_id: string;
  serial_number: string;
  name: string;
  location: string;
  co2_green: number;
  co2_yellow: number;
  co2_red: number;
}

const DeviceDataModalForm = ({
  opened,
  onClose,
  onSubmit,
  initialValues,
}: DeviceModalFormProps) => {
  const form = useForm<DeviceFormValues>({
    initialValues: {
      device_id: initialValues?.device_id || "",
      serial_number: initialValues?.serial_number || "",
      name: initialValues?.name || "",
      location: initialValues?.location || "",
      co2_green: initialValues?.co2_green || 0,
      co2_yellow: initialValues?.co2_yellow || 0,
      co2_red: initialValues?.co2_red || 0,
    },  
  });

  useEffect(() => {
    form.setInitialValues({
      device_id: initialValues?.device_id || "",
      serial_number: initialValues?.serial_number || "",
      name: initialValues?.name || "",
      location: initialValues?.location || "",
      co2_green: initialValues?.co2_green || 0,
      co2_yellow: initialValues?.co2_yellow || 0,
      co2_red: initialValues?.co2_red || 0,
    });
  }, [initialValues]);

  console.log(initialValues);
  return (
    <Modal
      opened={opened}
      onClose={onClose}
      title={initialValues ? "Edit device" : "Add device"}
    >
      <form onSubmit={form.onSubmit(onSubmit)}>
        <TextInput label="Name" {...form.getInputProps("name")} required />
        <TextInput
          label="Serial Number"
          disabled={!!initialValues}
          {...form.getInputProps("serial_number")}
          required
        />
        <TextInput
          label="Location"
          {...form.getInputProps("location")}
          required
        />
        <NumberInput
          label="CO2 Green Level"
          {...form.getInputProps("co2_green")}
          required
        />
        <NumberInput
          label="CO2 Yellow Level"
          {...form.getInputProps("co2_yellow")}
          required
        />
        <NumberInput
          label="CO2 Red Level"
          {...form.getInputProps("co2_red")}
          required
        />
        <Group justify="flex-end" mt="md">
          <Button type="submit">Save changes</Button>
        </Group>
      </form>
    </Modal>
  );
};

export default DeviceDataModalForm;
